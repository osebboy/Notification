<?php
/**
 * Notification: Event based notification system for PHP
 * 
 * Copyright (c) 2010 - 2011, Omercan Sebboy <osebboy@gmail.com>. 
 * All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE file 
 * that was distributed with this source code.
 *
 * @author     Omercan Sebboy (www.osebboy.com)
 * @package    Notification\Tests
 * @copyright  Copyright(c) 2010 - 2011, Omercan Sebboy (osebboy@gmail.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    2.0
 */
namespace Notification\Tests;

use Notification\StaticDispatcher;

require_once __DIR__ . '/../TestHelper.php';

/**
 * StaticDispatcher Test
 * 
 * Since this class works on the instance of Notification\Dispatcher, tests 
 * would be the same except for Notify method which required the arguments
 * array to be modified.
 */
class StaticDispatcherTest extends \PHPUnit_Framework_TestCase 
{
	protected function setUp() 
	{
	}

	protected function tearDown() 
	{
	}

	public function testNotifyCanBeCalledUpToSixArguments()
	{
		StaticDispatcher::attach('event', 'foo', 'foo_multiple');
		$it = StaticDispatcher::notify('event', 'one', 'two', 'three');
		$this->assertEquals('foo_onetwothree', $it[0]);
		
		StaticDispatcher::attach('test', 'bar', 'bar_test');
		$test = StaticDispatcher::notify('test', 1,2,3,4,5,6);
		$this->assertEquals('123456', $test[0]);
	}
}