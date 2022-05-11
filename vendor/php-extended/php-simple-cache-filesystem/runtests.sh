#!/bin/bash
set -eu
IFS=$'\n\t'

CURRDIR="$(dirname "$0")"
CURRDIR="$(realpath "$CURRDIR")"
PAREDIR="$(realpath "$CURRDIR/..")"
cd "$CURRDIR"

# {{{ BEGIN CHECK FOR BINARIES

set +e
command -v jq
RES=$?
set -e
if [ $RES != 0 ]
then
	echo "Failed to find jq, please install it"
	exit 1
fi

# }}} END CHECK FOR BINARIES


# {{{ BEGIN GENERATE PHPUNIT FILES

printf "\n"

if [ ! -d "$CURRDIR/test" ]
then
	mkdir "$CURRDIR/test"
fi

for FILE in $(find ./src -maxdepth 1 -name '*.php')
do
	
	TESTFILENAME=$(basename "$FILE")
	OBJCLASSNAME="${TESTFILENAME:0:-4}"
	TESTCLASSNAME="${OBJCLASSNAME}Test"
	TESTFILENAME="$TESTCLASSNAME.php"
	TESTFILEPATH=$(realpath "./test/$TESTFILENAME")
	
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] Checking test file $TESTFILENAME"
	[ -f "$TESTFILEPATH" ] && continue
	
	NAMESPACE=$(cat "$CURRDIR/composer.json" | jq '.autoload."psr-4" | keys[0]' | sed 's/"//g' | sed 's/\\\\/\\/g' | sed 's/\\$//g')
	
	cat > "$TESTFILEPATH" <<EOF
<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use $NAMESPACE\\$OBJCLASSNAME;

/**
 * $TESTCLASSNAME test file.
 * 
 * @author Anastaszor
 * @covers \\$NAMESPACE\\$OBJCLASSNAME
 */
class $TESTCLASSNAME extends TestCase
{
	
	/**
	 * The object to test.
	 * 
	 * @var $OBJCLASSNAME
	 */
	protected \$_object;
	
	public function testToString() : void
	{
		\$this->assertEquals(get_class(\$this->_object).'@'.spl_object_hash(\$this->_object), \$this->_object->__toString());
	}
	
	/**
	 * {@inheritdoc}
	 * @see \PHPUnit\Framework\TestCase::setUp()
	 */
	protected function setUp() : void
	{
		\$this->_object = new $OBJCLASSNAME();
	}
	
	/**
	 * {@inheritdoc}
	 * @see \PHPUnit\Framework\TestCase::tearDown()
	 */
	protected function tearDown() : void
	{
		\$this->_object = null;
	}
	
}
EOF
	
done

# }}} END GENERATE PHPUNIT FILES


# {{{ BEGIN FORMAT composer.json

printf "\n"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] REORDERING : $CURRDIR/composer.json"
CURRCOMP=$(cat "$CURRDIR/composer.json")
# reorder composer.json according to schema
# https://getcomposer.org/doc/04-schema.md
# except minimum stability
# pretty print with tabs
# then ignore null fields
# then add space before colon
echo $CURRCOMP | jq --tab '{ 
	name: .name,
	description: .description,
	version: .version,
	type: .type,
	keywords: .keywords,
	homepage: .homepage,
	readme: .readme,
	time: .time,
	license: .license,
	authors: .authors,
	support: .support,
	funding: .funding,
	require: .require,
	"require-dev": ."require-dev",
	conflict: .conflict,
	replace: .replace,
	provide: .provide,
	suggest: .suggest,
	autoload: .autoload,
	"autoload-dev": ."autoload-dev",
	"include-path": ."include-path",
	"target-dir": ."target-dir",
	"prefer-stable": ."prefer-stable",
	repositories: .repositories,
	config: .config,
	scripts: .scripts,
	extra: .extra,
	bin: .bin,
	archive: .archive,
	abandoned: .abandoned,
	"non-feature-branches": ."non-feature-branches"
} | del(.[] | nulls)' | sed 's/":/" :/g' | head -c -1 > "$CURRDIR/composer.json"

# }}} END FORMAT composer.json

# {{{ BEGIN RUN COMPOSER

printf "\n"
set +e
OLDCPSR=$(find "$PAREDIR/composer.phar" -mtime +7 -print)
set -e
if [ ! -f "$PAREDIR/composer.phar" ] || [ -n "$OLDCPSR" ]
then
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DOWNLOADING composer.phar"
	curl --location --progress-bar --fail --show-error https://getcomposer.org/composer.phar --output "$PAREDIR/composer.phar"
else
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DO NOT DOWNLOAD composer.phar : not old enough"
fi

if [ ! -f "$CURRDIR/vendor/autoload.php" ]
then
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] INSTALLING composer dependancies"
	php "$PAREDIR/composer.phar" install --ansi --no-interaction --no-progress --prefer-dist
else
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] UPGRADING composer dependancies"
	php "$PAREDIR/composer.phar" update --ansi --no-interaction --no-progress --prefer-dist
fi

# }}} END RUN COMPOSER

# {{{ BEGIN RUN PHP-CS-FIXER

printf "\n"
set +e
OLDCSFX=$(find "$PAREDIR/php-cs-fixer.phar" -mtime +7 -print)
set -e
if [ ! -f "$PAREDIR/php-cs-fixer.phar" ] || [ -n "$OLDCSFX" ]
then
	VERSION=$(curl --location --progress-bar --fail --show-error https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/git/refs/tags | jq '.[-1].ref' | sed 's/"//g' | sed 's/refs\/tags\///g')
	RELEASE_URL="https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/${VERSION}/php-cs-fixer.phar"
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DOWNLOAD : $RELEASE_URL"
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] WRITE TO : $PAREDIR/php-cs-fixer.phar"
	curl --location --progress-bar --fail --show-error "$RELEASE_URL" --output "$PAREDIR/php-cs-fixer.phar"
else
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DO NOT INSTALL php-cs-fixer.phar : not old enough"
fi

echo "[$(date '+%Y-%m-%d %H:%M:%S')] RUN PHP CS FIXER"
php "$PAREDIR/php-cs-fixer.phar" fix -vvv --allow-risky=yes

# }}} END RUN PHP-CS-FIXER


# {{{ BEGIN BAN OF MIXED
printf "\n"
echo "[$(date '+%Y-%m-%d %H:%M:%S')] CHECK FOR 'MIXED' VALUES IN SOURCE CODE"
set +e
grep -r 'mixed' ./src --include="*.php"
RES=$?
set -e
if [ $RES == 0 ]
then
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] There are still 'mixed' values in the source code, please expand it with null|boolean|integer|float|string|object|array<integer|string, null|boolean|integer|float|string|object|array>"
	exit 1
fi

# }}} END BAN OF MIXED

# {{{ BEGIN BAN OF SELF AS RETURN TYPE
printf "\n"
echo "[$(date '+%Y-%m-%d %H:%M:%S')] CHECK FOR ': SELF' VALUES IN SOURCE CODE"
set +e
grep -r ': self' ./src --include="*.php"
RES=$?
set -e
if [ $RES == 0 ]
then
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] There are still ': self' return types values in the code, change it to the interface name."
	exit 2
fi
# }}} END BAN OF self


# {{{ BEGIN RUN PHPSTAN

printf "\n"
set +e
OLDPSTN=$(find "$PAREDIR/phpstan.phar" -mtime +7 -print)
set -e
if [ ! -f "$PAREDIR/phpstan.phar" ] || [ -n "$OLDPSTN" ]
then
	RELEASE_URL=$(curl --location --progress-bar --fail --show-error https://api.github.com/repos/phpstan/phpstan/releases | jq '[.[]|.assets|.[]|.browser_download_url][0]' | sed 's/"//g')
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DOWNLOAD : $RELEASE_URL"
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] WRITE TO : $PAREDIR/phpstan.phar"
	curl --location --progress-bar --fail --show-error "$RELEASE_URL" --output "$PAREDIR/phpstan.phar"
else
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DO NOT INSTALL phpstan.phar : not old enough"
fi

echo "[$(date '+%Y-%m-%d %H:%M:%S')] RUN PHPSTAN"
php "$PAREDIR/phpstan.phar" --version
php "$PAREDIR/phpstan.phar" analyse --configuration="$CURRDIR/phpstan.neon" --error-format=table --memory-limit 2G

# }}} END RUN PHPSTAN


# {{{ BEGIN RUN PSALM

printf "\n"
set +e
OLDPSLM=$(find "$PAREDIR/psalm.phar" -mtime +7 -print)
set -e
if [ ! -f "$PAREDIR/psalm.phar" ] || [ -n "$OLDPSLM" ]
then
	RELEASE_URL=$(curl --location --progress-bar --fail --show-error https://api.github.com/repos/vimeo/psalm/releases | jq '[.[]|.assets|.[]|.browser_download_url][0]' | sed 's/"//g')
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DOWNLOAD : $RELEASE_URL"
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] WRITE TO : $PAREDIR/psalm.phar"
	curl --location --progress-bar --fail --show-error "$RELEASE_URL" --output "$PAREDIR/psalm.phar"
else
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DO NOT INSTALL psalm.phar : not old enough"
fi

echo "[$(date '+%Y-%m-%d %H:%M:%S')] CLEAR PSALM CACHE"
rm -rf ~/.cache/psalm
echo "[$(date '+%Y-%m-%d %H:%M:%S')] RUN PSALM"
php "$PAREDIR/psalm.phar" --version
php "$PAREDIR/psalm.phar" --config="$CURRDIR/psalm.xml" --output-format=console --long-progress --stats --show-info=true

# }}} END RUN PSALM


# {{{ BEGIN RUN PHPMD

printf "\n"
set +e
OLDPHMD=$(find "$PAREDIR/phpmd.phar" -mtime +7 -print)
set -e
if [ ! -f "$PAREDIR/phpmd.phar" ] || [ -n "$OLDPHMD" ]
then
	RELEASE_URL=$(curl --location --progress-bar --fail --show-error https://api.github.com/repos/phpmd/phpmd/releases | jq '[.[]|.assets|.[]|.browser_download_url][0]' | sed 's/"//g')
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DOWNLOAD : $RELEASE_URL"
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] WRITE TO : $PAREDIR/phpmd.phar"
	curl --location --progress-bar --fail --show-error "$RELEASE_URL" --output "$PAREDIR/phpmd.phar"
else
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DO NOT INSTALL phpmd.phar : not old enough"
fi

echo "[$(date '+%Y-%m-%d %H:%M:%S')] RUN PHPMD"
php "$PAREDIR/phpmd.phar" --version
php "$PAREDIR/phpmd.phar" "$CURRDIR/src" ansi "$CURRDIR/phpmd.xml"

# }}} END RUN PHPMD


# {{{ BEGIN RUN PHPUNIT

printf "\n"
set +e
OLDPHPUNIT=$(find "$PAREDIR/phpunit.phar" -mtime +7 -print)
set -e
if [ ! -f "$PAREDIR/phpunit.phar" ] || [ -n "$OLDPHPUNIT" ]
then
	RELEASE_URL="https://phar.phpunit.de/phpunit-9.phar"
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DOWNLOAD : $RELEASE_URL"
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] WRITE TO : $PAREDIR/phpunit.phar"
	curl --location --progress-bar --fail --show-error "$RELEASE_URL" --output "$PAREDIR/phpunit.phar"
else
	echo "[$(date '+%Y-%m-%d %H:%M:%S')] DO NOT INSTALL phpunit.phar : not old enough"
fi

rm -rf "$CURRDIR/build/coverage"
echo "[$(date '+%Y-%m-%d %H:%M:%S')] RUN PHPUNIT"
php "$PAREDIR/phpunit.phar" --configuration "$CURRDIR/phpunit.xml" --coverage-text
rm -f "$CURRDIR/.phpunit.result.cache"

# }}} END RUN PHPUNIT
