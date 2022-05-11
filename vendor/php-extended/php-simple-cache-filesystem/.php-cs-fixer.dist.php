<?php declare(strict_types=1);

/*
 * This file is part of the dirname(__FILE__) library.
 * 
 * (c) Anastaszor
 * This source file is subject to the MIT license that
 * is bundled with this source code in the file LICENSE.
 */

namespace Composer\Semver;

// remove IDE's non existant class errors on composer installs
// because of the use of composer.phar
if(!\class_exists('Composer\Semver\VersionParser'))
{
	class VersionParser {}
}

namespace PhpCsFixer;

// remove IDE's non existant class errors
// because of the use of php-cs-fixer.phar
if(!\class_exists('PhpCsFixer\Finder'))
{
	class Finder {}
}

if(!\class_exists('PhpCsFixer\Config'))
{
	class Config {}
}

$finder = \PhpCsFixer\Finder::create()
	->in(__DIR__.DIRECTORY_SEPARATOR.'src')
;

if(is_dir(__DIR__.DIRECTORY_SEPARATOR.'test'))
	$finder->in(__DIR__.DIRECTORY_SEPARATOR.'test');

$composer_file = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'composer.json');
$composer_json = json_decode($composer_file, true);

$libname = $composer_json['name'];
$header = <<<EOF
This file is part of the $libname library

(c) Anastaszor
This source file is subject to the MIT license that
is bundled with this source code in the file LICENSE.
EOF;

$doctrine_ignored_tags = [
	'abstract', 'access', 'after', 'afterClass', 'api', 'author',
	'backupGlobals', 'backupStaticAttributes', 'before', 'beforeClass',
	'category', 'copyright', 'code', 'covers', 'coversDefaultClass', 'coversNothing', 'codeCoverageIgnore', 'codeCoverageIgnoreStart', 'codeCoverageIgnoreEnd',
	'deprec', 'deprecated', 'dataProvider', 'depends',
	'encode', 'enduml', 'exception', 'example', 'expectedException', 'expectedExceptionCode', 'expectedExceptionMessage', 'expectedExceptionMessageRegExp', 'extends',
	'final', 'filesource', 'fix', 'FIXME', 'fixme',
	'group', 'global',
	'ingroup', 'implements', 'ignore', 'internal', 'inheritdoc', 'inheritDoc',
	'large', 'license', 'link',
	'magic', 'method', 'medium',
	'name', 'noinspection',
	'override',
	'package', 'package_version', 'param', 'preserveGlobalState', 'private', 'property', 'property-read', 'property-write',
	'return', 'requires', 'runTestsInSeparateProcesses', 'runInSeparateProcess',
	'see', 'since', 'small', 'source', 'static', 'staticvar', 'staticVar', 'startuml', 'subpackage', 'SuppressWarnings',
	'test', 'testdox', 'ticket', 'throw', 'throws', 'toc', 'todo', 'TODO',  'tutorial',
	'uses', 'usedBy',
	'var', 'version',
];

$config = (new \PhpCsFixer\Config())
	->setUsingCache(false)
	->setRiskyAllowed(false)
	->setRules([
		// @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/3.0/doc/rules/index.rst
		
		// Alias Rules
		'array_push' => true, // risky
		'backtick_to_shell_exec' => true,
		'ereg_to_preg' => true,     // risky
		'mb_str_functions' => true, // risky
		'no_alias_functions' => ['sets' => ['@all']], // risky
		'no_alias_language_construct_call' => true,
		'no_mixed_echo_print' => ['use' => 'echo'],
		'pow_to_exponentiation' => true, // risky
		'random_api_migration' => [ // risky
			'replacements' => [
				'getrandmax' => 'mt_getrandmax',
				'rand' => 'mt_rand',
				'srand' => 'mt_srand'
			],
		],
		'set_type_to_cast' => true, // risky
		
		
		// Array Notation Rules
		'array_syntax' => ['syntax' => 'short'],
		'no_multiline_whitespace_around_double_arrow' => true,
		'no_trailing_comma_in_singleline_array' => true,
		'no_whitespace_before_comma_in_array' => ['after_heredoc' => false],
		'normalize_index_brace' => true,
		'trailing_comma_in_multiline' => ['after_heredoc' => false],
		'trim_array_spaces' => true,
		'whitespace_after_comma_in_array' => true,
		
		
		// Basic Rules
		/* 'braces' => [
			'allow_single_line_anonymous_class_with_empty_body' => true,
			'allow_single_line_closure' => true,
			'position_after_functions_and_oop_constructs' => 'next',
			'position_after_control_structures' => 'next',
			'position_after_anonymous_constructs' => 'next',
		], */
		'encoding' => true,
		'non_printable_character' => [ // risky
			'use_escape_sequences_in_strings' => true
		],
		'psr_autoloading' => ['dir' => null], // risky
		
		
		// Casing Rules
		'constant_case' => ['case' => 'lower'],
		'lowercase_keywords' => true,
		'lowercase_static_reference' => true,
		'magic_constant_casing' => true,
		'magic_method_casing' => true,
		'native_function_casing' => true,
		'native_function_type_declaration_casing' => true,
		
		
		// Cast Notation Rules
		'cast_spaces' => ['space' => 'single'],
		'lowercase_cast' => true,
		'modernize_types_casting' => true, // risky
		'no_short_bool_cast' => true,
		'no_unset_cast' => true,
		'short_scalar_cast' => true,
		
		
		// Class Notation Rules
		/* 'class_attributes_separation' => [
			'elements' => [
				'const' => 'none',
				'method' => 'one',
				'property' => 'one',
			],
		], */
		'class_definition' => [
			'multi_line_extends_each_single_line' => false,
			'single_item_single_line' => true,
			'single_line' => true,
		],
		'final_class' => false, // risky
		'final_internal_class' => false, // risky
		'final_public_method_for_abstract_class' => false, // risky
		'no_blank_lines_after_class_opening' => false, // i'd prefer have exactly one
		'no_null_property_initialization' => true,
		'no_php4_constructor' => true,
		'no_unneeded_final_method' => true, // risky
		'ordered_class_elements' => [
			'order' => [
				'use_trait', 'constant_public', 'constant_protected', 'constant_private',
				'property_public_static', 'property_protected_static', 'property_private_static',
				'method_public_static', 'method_protected_static', 'method_private_static',
				'property_public', 'property_protected', 'property_private',
				'construct', 'magic', 'method_private', 'method_protected', 'method_public',
				'phpunit', 'destruct',
			],
			'sort_algorithm' => 'none',
		],
		'ordered_interfaces' => [
			'direction' => 'ascend',
			'order' => 'alpha',
		],
		'ordered_traits' => true,
		'protected_to_private' => false, // i'd prefer private to protected
		'self_accessor' => false, // i'd prefer the opposite
		'self_static_accessor' => false, // i'd prefer the opposite
		'single_class_element_per_statement' => [
			'elements' => ['const', 'property'],
		],
		'single_trait_insert_per_statement' => false, // i'd prefer the opposite
		'visibility_required' => [
			'elements' => ['property', 'method', 'const'],
		],
		
		
		// Class Usage Rules
		'date_time_immutable' => true, // risky
		
		
		// Comment Rules
		'comment_to_phpdoc' => ['ignored_tags' => [
			'codeCoverageIgnoreStart',
			'codeCoverageIgnoreEnd',
		]], // risky
		'header_comment' => [
			'header' => $header,
			'comment_type' => 'comment',
			'location' => 'after_declare_strict',
			'separate' => 'both',
		],
		'multiline_comment_opening_closing' => true,
		'no_empty_comment' => true,
		'no_trailing_whitespace_in_comment' => false,
		'single_line_comment_style' => [
			'comment_types' => ['asterisk', 'hash'],
		],
		
		
		// Constant Notation Rules
		'native_constant_invocation' => [ // risky
			'exclude' => ['null', 'false', 'true'],
			'fix_built_in' => true,
			'include' => [],
			'scope' => 'all',
		],
		
		
		// Control Structure Rules
		'elseif' => true,
		'include' => true,
		'no_alternative_syntax' => true,
		'no_break_comment' => ['comment_text' => 'No break, cascades'],
		'no_superfluous_elseif' => true,
		'no_trailing_comma_in_list_call' => true,
		'no_unneeded_control_parentheses' => [
			'statements' => [
				'break', 'clone', 'continue', 'echo_print',
				'return', 'switch_case', 'yield',
			],
		],
		'no_unneeded_curly_braces' => ['namespaces' => true],
		'no_useless_else' => true,
		'simplified_if_return' => true,
		'switch_case_semicolon_to_colon' => true,
		'switch_case_space' => true,
		'switch_continue_to_break' => true,
		'yoda_style' => [
			'always_move_variable' => true,
			'equal' => true,
			'identical' => true,
			'less_and_greater' => true,
		],
		
		
		// Doctrine Annotation Rules
		'doctrine_annotation_array_assignment' => [
			'ignored_tags' => $doctrine_ignored_tags,
			'operator' => '=',
		],
		'doctrine_annotation_braces' => [
			'ignored_tags' => $doctrine_ignored_tags,
			// 'syntax' => 'with_braces', // risky, may break things with unknown tags
		],
		'doctrine_annotation_indentation' => [
			'ignored_tags' => $doctrine_ignored_tags,
			'indent_mixed_lines' => true,
		],
		'doctrine_annotation_spaces' => [
			'ignored_tags' => $doctrine_ignored_tags,
			'after_argument_assignments' => true,
			'after_array_assignments_colon' => true,
			'after_array_assignments_equals' => true,
			'around_commas' => true,
			'around_parentheses' => true,
			'before_argument_assignments' => true,
			'before_array_assignments_colon' => true,
			'before_array_assignments_equals' => true,
		],
		
		
		// Function Notation Rules
		'combine_nested_dirname' => true, // risky
		'fopen_flag_order' => true, // risky
		'fopen_flags' => ['b_mode' => false], // risky
		'function_declaration' => ['closure_function_spacing' => 'none'],
		'function_typehint_space' => true,
		'implode_call' => true, // risky
		'lambda_not_used_import' => true,
		'method_argument_space' => [
			'after_heredoc' => false,
			'keep_multiple_spaces_after_comma' => false,
			'on_multiline' => 'ensure_fully_multiline',
		],
		'native_function_invocation' => [ // risky
			'exclude' => [],
			'include' => ['@internal'],
			'scope' => 'all',
			'strict' => false,
		],
		'no_spaces_after_function_name' => true,
		'no_unreachable_default_argument_value' => true, // risky
		'no_useless_sprintf' => true,
		'nullable_type_declaration_for_default_null_value' => [
			'use_nullable_type_declaration' => true,
		],
		'phpdoc_to_param_type' => false, // risky // experimental
		'phpdoc_to_return_type' => false, // risky // experimental
		'regular_callable_call' => true, // risky
		'return_type_declaration' => ['space_before' => 'one'],
		'single_line_throw' => true,
		'static_lambda' => false, // risky
		'use_arrow_functions' => false, // risky
		'void_return' => true, // risky
		
		
		// Import Rules
		'fully_qualified_strict_types' => true,
		'global_namespace_import' => [
			'import_classes' => true,
			'import_constants' => false,
			'import_functions' => false,
		],
		'group_import' => false,
		'no_leading_import_slash' => true,
		'no_unused_imports' => true,
		'ordered_imports' => [
			'sort_algorithm' => 'alpha',
			'imports_order' => ['class', 'const', 'function'],
		],
		'single_import_per_statement' => true,
		'single_line_after_imports' => true,
		
		
		// Language Construct Rules
		'combine_consecutive_issets' => true,
		'combine_consecutive_unsets' => true,
		'declare_equal_normalize' => ['space' => 'none'],
		'dir_constant' => true, // risky
		'error_suppression' => [
			'mute_deprecation_error' => true,
			'noise_remaining_usages' => false,
			'noise_remaining_usages_exclude' => [],
		],
		'explicit_indirect_variable' => true,
		'function_to_constant' => [
			'functions' => [
				'get_called_class', 'get_class', 'get_class_this',
				'php_sapi_name', 'phpversion', 'pi'
			],
		],
		'is_null' => true, // risky
		'no_unset_on_property' => true, // risky
		'single_space_after_construct' => [
			'constructs' => [
				'abstract', 'as', 'attribute', 'break', 'case',
				'class', 'clone', 'comment', 'const', 'const_import',
				'continue', 'echo', 'extends',
				'final',
				'function_import', 'global', 'goto', 'implements',
				'include', 'include_once', 'instanceof', 'insteadof',
				'interface', 'named_argument', 'new',
				'open_tag_with_echo', 'php_doc', 'php_open',
				'private', 'protected', 'public', 'require', 'require_once',
				'return', 'static', 'throw', 'trait', 'use',
				'use_trait', 'var', 'yield', 'yield_from'
			],
		],
		
		
		// List Notation Rules
		'list_syntax' => ['syntax' => 'short'],
		
		
		// Namespace Notation Rules
		'blank_line_after_namespace' => true,
		'clean_namespace' => true,
		'no_blank_lines_before_namespace' => false, // conflicts single_blank_line_before_namespace
		'no_leading_namespace_whitespace' => true,
		'single_blank_line_before_namespace' => true,
		
		
		// Naming Rules
		'no_homoglyph_names' => true, // risky
		
		// Operator Rules
		'binary_operator_spaces' => [
			'default' => 'single_space',
			// all operators as default, '=' and '=>' included
			// only exception is '.', managed with 'concat_space' rule
		],
		'concat_space' => ['spacing' => 'none'],
		'increment_style' => ['style' => 'post'],
		'logical_operators' => true, // risky
		'new_with_braces' => true,
		'not_operator_with_space' => false,
		'not_operator_with_successor_space' => false,
		'object_operator_without_whitespace' => true,
		'operator_linebreak' => [
			'only_booleans' => false,
			'position' => 'beginning',
		],
		'standardize_increment' => true,
		'standardize_not_equals' => true,
		'ternary_operator_spaces' => true,
		'ternary_to_elvis_operator' => true, // risky
		'ternary_to_null_coalescing' => true,
		'unary_operator_spaces' => true,
		
		
		// PHP Tag Rules
		'blank_line_after_opening_tag' => false,
		'echo_tag_syntax' => [
			'format' => 'long',
			'long_function' => 'echo',
			'shorten_simple_statements_only' => true,
		],
		'full_opening_tag' => true,
		'linebreak_after_opening_tag' => false,
		'no_closing_tag' => true,
		
		
		// PHPUnit Rules
		'php_unit_construct' => [
			'assertions' => [
				'assertSame', 'assertEquals',
				'assertNotEquals', 'assertNotSame',
			],
		],
		'php_unit_dedicate_assert' => ['target' => 'newest'], // risky
		'php_unit_dedicate_assert_internal_type' => ['target' => 'newest'], // risky
		'php_unit_expectation' => ['target' => 'newest'], // risky
		'php_unit_fqcn_annotation' => true,
		'php_unit_internal_class' => ['types' => ['normal', 'final', 'abstract']],
		'php_unit_method_casing' => ['case' => 'camel_case'],
		'php_unit_mock' => ['target' => 'newest'], // risky
		'php_unit_mock_short_will_return' => true,
		'php_unit_namespaced' => ['target' => 'newest'], // risky
		'php_unit_no_expectation_annotation' => [ // risky
			'target' => 'newest',
			'use_class_const' => true,
		],
		'php_unit_set_up_tear_down_visibility' => true,
		'php_unit_size_class' => ['group' => 'small'], // @small, @medium, @large
		'php_unit_strict' => false,
		'php_unit_test_annotation' => ['style' => 'prefix'], // risky
		'php_unit_test_case_static_method_calls' => [
			'call_type' => 'this',
			'methods' => [],
		],
		'php_unit_test_class_requires_covers' => true,
		
		
		// PHPDoc Rules
		'align_multiline_comment' => ['comment_type' => 'all_multiline'],
		'general_phpdoc_annotation_remove' => [
			'annotations' => ['package', 'subpackage'],
		],
		'general_phpdoc_tag_rename' => [
			'fix_annotation' => true,
			'fix_inline' => true,
			'replacements' => ['inheritDocs' => 'inheritDoc'],
			'case_sensitive' => false,
		],
		'no_blank_lines_after_phpdoc' => true, // disabled ?
		'no_empty_phpdoc' => true,
		'no_superfluous_phpdoc_tags' => false,
		'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
		'phpdoc_align' => [
			'align' => 'left',
			'tags' => [
				'param', 'property', 'return', 'throws', 'type', 'var', 'method'
			],
		],
		'phpdoc_annotation_without_dot' => true,
		'phpdoc_indent' => true,
		'phpdoc_inline_tag_normalizer' => [
			'tags' => [
				'example', 'id', 'internal', 'inheritdoc', 'inheritdocs',
				'link', 'source', 'toc', 'tutorial',
			],
		],
		'phpdoc_line_span' => [
			'const' => 'multi',
			'method' => 'multi',
			'property' => 'multi',
		],
		'phpdoc_no_access' => true,
		'phpdoc_no_alias_tag' => [
			'replacements' => [
				'property-read' => 'property', 'property-write' => 'property',
				'type' => 'var', 'link' => 'see'
			],
		],
		'phpdoc_no_empty_return' => true,
		'phpdoc_no_package' => true,
		'phpdoc_no_useless_inheritdoc' => true,
		'phpdoc_order_by_value' => [
			'annotations' => [
				'author',
				'covers',
				'coversNothing',
				'dataProvider',
				'depends',
				'group',
				'internal',
				// 'method', // model classes have it in specific order
				// 'param', // let the actual order of the method/func params
				// 'property', // model classes have it in specific order
				// 'property-read', // model classes have it in specific order
				// 'property-write', // model classes have it in specific order
				'requires',
				'throws',
				'uses',
			],
		],
		'phpdoc_order' => false, // cant configure to @param, @return, @throws
		'phpdoc_return_self_reference' => [
			'replacements' => [
				'this' => 'static', '@this' => 'static',
				'$self' => 'self', '@self' => 'self',
				'$static' => 'static', '@static' => 'static',
			],
		],
		'phpdoc_scalar' => [
			'types' => [
				// cant fix bool to boolean
				// cant fix int to integer
				'callback', 'double', 'real', 'str',
			],
		],
		'phpdoc_separation' => false,
		'phpdoc_single_line_var_spacing' => true,
		'phpdoc_summary' => true,
		'phpdoc_tag_casing' => ['tags' => ['inheritDoc']],
		'phpdoc_tag_type' => [
			'tags' => [
				'api' => 'annotation',
				'author' => 'annotation',
				'copyright' => 'annotation',
				'deprecated' => 'annotation',
				'example' => 'annotation',
				'global' => 'annotation',
				'inheritDoc' => 'inline',
				'internal' => 'annotation',
				'license' => 'annotation',
				'method' => 'annotation',
				'package' => 'annotation',
				// 'param' => 'annotation', // failed to process array{0: string}
				'property' => 'annotation',
				// 'return' => 'annotation', // failed to process array{0: string}
				'see' => 'annotation',
				'since' => 'annotation',
				'throws' => 'annotation',
				'todo' => 'annotation',
				'uses' => 'annotation',
				'var' => 'annotation',
				'version' => 'annotation',
			],
		],
		'phpdoc_to_comment' => false, // @psalm-zzz not recognized as annotations
		'phpdoc_trim_consecutive_blank_line_separation' => true,
		'phpdoc_trim' => true,
		'phpdoc_types' => ['groups' => ['simple', 'alias', 'meta']],
		'phpdoc_types_order' => [
			'sort_algorithm' => 'none',
			'null_adjustment' => 'always_first',
		],
		'phpdoc_var_annotation_correct_order' => true,
		'phpdoc_var_without_name' => true,
		
		
		// Return Notation Rules
		'no_useless_return' => true,
		'return_assignment' => true,
		'simplified_null_return' => false,
		
		
		// Semicolon Rules
		'multiline_whitespace_before_semicolons' => [
			'strategy' => 'new_line_for_chained_calls',
		],
		'no_empty_statement' => true,
		'no_singleline_whitespace_before_semicolons' => true,
		'semicolon_after_instruction' => true,
		'space_after_semicolon' => [
			'remove_in_empty_for_expressions' => false,
		],
		
		
		// Strict Rules
		'declare_strict_types' => true, // risky
		'strict_comparison' => true, // risky
		'strict_param' => false, // risky
		
		
		// String Notation Rules
		'escape_implicit_backslashes' => [
			'double_quoted' => true,
			'heredoc_syntax' => true,
			'single_quoted' => false,
		],
		'explicit_string_variable' => true,
		'heredoc_to_nowdoc' => false,
		'no_binary_string' => true,
		'no_trailing_whitespace_in_string' => false,
		'simple_to_complex_string_variable' => false,
		'single_quote' => [
			'strings_containing_single_quote_chars' => false,
		],
		'string_line_ending' => false, // risky
		
		
		// Whitespace Rules
		'array_indentation' => true,
		'blank_line_before_statement' => [
			'statements' => [
				'case', 'for', 'foreach', 'return',
				'switch', 'throw', 'try', 'while',
			],
		],
		'compact_nullable_typehint' => true,
		'heredoc_indentation' => false,
		'indentation_type' => true,
		'line_ending' => true,
		'method_chaining_indentation' => true,
		'no_extra_blank_lines' => false,
		'no_spaces_around_offset' => ['positions' => ['inside', 'outside']],
		'no_spaces_inside_parenthesis' => true,
		'no_trailing_whitespace' => false,
		'no_whitespace_in_blank_line' => false,
		'single_blank_line_at_eof' => true,
	])
	->setIndent("\t")
	->setLineEnding("\n")
	->setFinder($finder)
;

return $config;
