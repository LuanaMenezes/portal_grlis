<?php declare(strict_types=1);

/*
 * This file is part of the php-extended/php-simple-cache-filesystem library
 *
 * (c) Anastaszor
 * This source file is subject to the MIT license that
 * is bundled with this source code in the file LICENSE.
 */

namespace PhpExtended\SimpleCache;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Serializable;
use Stringable;

/**
 * SimpleCacheFilesystem class file.
 *
 * This class makes a cache of any folder on a filesystem.
 *
 * @author Anastaszor
 */
class SimpleCacheKey implements Stringable
{
	
	/**
	 * The hashed value of the key.
	 * 
	 * @var string
	 */
	protected $_hash;
	
	/**
	 * Gets the hash corresponding for the given key.
	 *
	 * @param null|boolean|integer|float|string|object|array<integer|string, null|boolean|integer|float|string|object|array<integer|string, null|boolean|integer|float|string|object>> $key
	 * @throws SimpleCacheInvalidArgumentException
	 */
	public function __construct($key)
	{
		if(\is_object($key))
			$key = $this->serializeObject($key);
		
		if(\is_array($key))
			$key = \serialize($key);
		
		$this->_hash = (string) \hash('sha512', (string) $key);
	}
	
	/**
	 * {@inheritDoc}
	 * @see Stringable::__toString()
	 */
	public function __toString() : string
	{
		return $this->_hash;
	}
	
	/**
	 * Gets the last error from the language.
	 *
	 * @return string
	 */
	protected function getLastError() : string
	{
		$data = \error_get_last();
		if(null === $data)
			return '(no error)';
		
		return 'File : '.$data['file'].' ; Line : '.((string) $data['line']).' ; Message : '.$data['message'];
	}
	
	/**
	 * Serializes the given object.
	 * 
	 * @param object $key
	 * @return string
	 * @throws SimpleCacheInvalidArgumentException
	 */
	public function serializeObject($key) : string
	{
		if($key instanceof DateTimeInterface)
			return $key->format(DateTime::RFC3339_EXTENDED);
		
		if($key instanceof DateInterval)
			return $key->format('%RY%YM%MD%DH%HI%IS%SF%F');
		
		if(\method_exists($key, '__toString'))
			return (string) $key->__toString();
		
		if(\method_exists($key, 'serialize'))
			return (string) $key->serialize();
		
		if($key instanceof Serializable)
			return \serialize($key);
		
		throw new SimpleCacheInvalidArgumentException(\strtr('The object {thing} is not convertible to a key, no __toString() nor serialize() method, and does not implements the \Serializable interface.', ['{thing}' => \get_class($key)]));
	}
	
	/**
	 * Gets the first part of the hash.
	 * 
	 * @return string
	 */
	public function getFirstLevel() : string
	{
		return (string) \mb_substr($this->_hash, 0, 2, '8bit');
	}
	
	/**
	 * Gets the second level of the key.
	 * 
	 * @return string
	 */
	public function getSecondLevel() : string
	{
		return (string) \mb_substr($this->_hash, 2, 2, '8bit');
	}
	
	/**
	 * Gets the last level of the key.
	 * 
	 * @return string
	 */
	public function getThirdLevel() : string
	{
		return (string) \mb_substr($this->_hash, 4, null, '8bit');
	}
	
	/**
	 * Gets the full path to the given file represented by this key.
	 * 
	 * @param string $base
	 * @return string
	 */
	public function getPath(string $base) : string
	{
		return $base.'/'.$this->getFirstLevel().'/'.$this->getSecondLevel().'/'.$this->getThirdLevel();
	}
	
	/**
	 * Returns whether the directory levels for the given base exists.
	 * 
	 * @param string $base
	 * @return boolean
	 */
	public function ensureDirectoryLevelExists(string $base) : bool
	{
		$firstLvl = $base.'/'.$this->getfirstLevel();
		
		if(!\is_dir($firstLvl))
		{
			if(!\mkdir($firstLvl, 0775))
			{
				return false;
			}
		}
		
		$secondLvl = $firstLvl.'/'.$this->getSecondLevel();
		if(!\is_dir($secondLvl))
		{
			if(!\mkdir($secondLvl, 0775))
			{
				return false;
			}
		}
		
		return true;
	}
	
}
