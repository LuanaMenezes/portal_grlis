<?php declare(strict_types=1);

/*
 * This file is part of the php-extended/polyfill-php80-stringable library
 *
 * (c) Anastaszor
 * This source file is subject to the MIT license that
 * is bundled with this source code in the file LICENSE.
 */

/*
 * Handles symfony packages compatibility.
 *
 * Due to the way composer works, as this class is specifically declared as
 * available into the class map, composer may prioritize requiring this file
 * before the symfony implementation.
 */
$vendorDir = \dirname(__DIR__, 3);
$symfonyStringablePath = $vendorDir.'/symfony/polyfill-php80/Resources/stubs/Stringable.php';
if(\is_file($symfonyStringablePath))
{
	
	/*
	 * When the symfony implementation of \Stringable is present, it probably
	 * means it was required by symfony packages. As some of those packages
	 * may have classes that do not have the return type implemented, we better
	 * use the more compatible symfony implementation rather than this stricter
	 * implementation to avoid php complaining about classes extending the
	 * \Stringable that do not respect the full signature of this interface.
	 */
	require $symfonyStringablePath;
	
}


/**
 * The \Stringable interface MUST NOT be loaded in php8.0, and trying
 * to do so will result in a fatal error as core interfaces cannot be
 * overwritten.
 * Thus, we prevent any intentional or unintentional require of this file
 * to halt any program by avoiding to re-define the interface.
 * 
 * The interface must be put into an if statement, due to the way php handles
 * the declaration of new classes. A return statement is not enough and
 * interfaces are defined when php finised parsing the file and before it
 * executes the first lines of the current file that was parsed.
 * 
 * @psalm-suppress UnrecognizedStatement
 */
if(!\interface_exists('Stringable'))
{
	
	/**
	 * Stringable interface file.
	 *
	 * This interface implements the __toString() signature for objects as it is
	 * defined with the rfc : https://wiki.php.net/rfc/stringable
	 *
	 * Is it to note that the implementation of php8.0 will automatically give the
	 * core \Stringable interface to objects that implements __toString() but do
	 * not implement the \Stringable interface, which is a behavior that cannot be
	 * reproduiced with lower versions of php.
	 *
	 * The motivation for such interface is to help identify objects that can
	 * represent themselves as strings, which may be useful in a number of ways,
	 * for example having loggers that are passed objects, or to build select
	 * html tags out of object options that are human-readable for the users.
	 *
	 * This implementation strictly follows the signature of the rfc, and therefore
	 * is not compatible with php < 7.0
	 *
	 * @author Anastaszor
	 */
	interface Stringable
	{
		
		/**
		 * Returns a string representation of this object. Note that this method
		 * is destinated to human interpretation, and should not be relied upon as
		 * an identifier for objects.
		 *
		 * A basic implementation of this method is like :
		 * <pre>
		 *     return static::class.'@'.\spl_object_hash($this);
		 * </pre>
		 * Such implementation returns something that is unique to the object and
		 * visually similar to the toString() implementation of the method in the
		 * java language.
		 *
		 * This method MUST NOT throw exceptions !
		 * Before php 7.4 exceptions that are thrown with this method will result
		 * in the zend engine giving a FATAL ERROR and the program to terminate.
		 *
		 * @return string
		 * @see https://www.php.net/manual/fr/language.oop5.magic.php#object.tostring
		 */
		public function __toString() : string;
		
	}
	
}
