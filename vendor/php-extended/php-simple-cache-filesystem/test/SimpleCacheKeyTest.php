<?php declare(strict_types=1);

/*
 * This file is part of the php-extended/php-simple-cache-filesystem library
 *
 * (c) Anastaszor
 * This source file is subject to the MIT license that
 * is bundled with this source code in the file LICENSE.
 */

use PhpExtended\SimpleCache\SimpleCacheKey;
use PHPUnit\Framework\TestCase;

/**
 * SimpleCacheKeyTest test file.
 * 
 * @author Anastaszor
 * @covers \PhpExtended\SimpleCache\SimpleCacheKey
 *
 * @internal
 *
 * @small
 */
class SimpleCacheKeyTest extends TestCase
{	
	/**
	 * The object to test.
	 * 
	 * @var SimpleCacheKey
	 */
	protected $_object;
	
	public function testToString() : void
	{
		$this->assertEquals(\hash('sha512', 'key'), $this->_object->__toString());
	}
	
	/**
	 * {@inheritDoc}
	 * @see \PHPUnit\Framework\TestCase::setUp()
	 */
	protected function setUp() : void
	{
		$this->_object = new SimpleCacheKey('key');
	}
	
	/**
	 * {@inheritDoc}
	 * @see \PHPUnit\Framework\TestCase::tearDown()
	 */
	protected function tearDown() : void
	{
		$this->_object = null;
	}
	
}
