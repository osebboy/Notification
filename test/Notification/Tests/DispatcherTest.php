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

use Notification\Dispatcher;

require_once __DIR__ . '/../TestHelper.php';

/**
 * Dispatcher Test
 * 
 *
 */
class DispatcherTest extends \PHPUnit_Framework_TestCase 
{
	protected function setUp() 
	{
		
	}

	protected function tearDown() 
	{
	}
	
	public function testAttachShouldAddObserverArray()
	{
		$d = new Dispatcher();
		$d->attach('event', 'Foo', 'update');
		$observer = array(
			0 => array('Foo', 'update', array()
		));
		$this->assertEquals($observer, $d->getObservers('event'));
	}
	
	public function testAttachShouldAddMultipleEvents()
	{
		$d = new Dispatcher();
		$d->attach('event_1', 'class', 'method', array('config'));
		$d->attach('event_1', 'foo', 'test', array('config'));
		$d->attach('event_2', 'class', 'method', array('config'));
		$d->attach('event_3', 'class', 'method', array('config'));
		$d->attach('event_3', 'bar', 'test', array('config'));
		$d->attach('event_4', 'class', 'method', array('config'));
		$this->assertTrue(count($d->getEvents()) === 4);
	}
	
	public function testAttachContextCanBeClassNameOrObject()
	{
		$d = new Dispatcher();
		$d->attach('event', 'className', 'method', array('config'));
		$this->assertEquals(array(array('className', 'method', array('config'))), $d->getObservers('event'));
		
		$obj = new \stdClass();
		$d->attach('event2', $obj, 'method');
		$observers = $d->getObservers('event2');
		$this->assertEquals($observers[0][0], $obj);
	}
	
	public function testDetachShouldRemoveObserverReturnTrueIfRemoved()
	{
		$d = new Dispatcher();
		$d->attach('event', 'Bar', 'test');
		$this->assertTrue($d->detach('event', 'Bar', 'test') === TRUE);
		// already removed, so should return false
		$this->assertTRUE($d->detach('event', 'Bar', 'test') === FALSE);	
	}
	
	public function testDetachShouldUnsetEventIfNoObserversRemain()
	{
		$d = new Dispatcher();
		$d->attach('event', 'foo', 'test');
		$this->assertTrue(count($d->getEvents()) === 1);
		$this->assertTrue($d->detach('event', 'foo', 'test') === TRUE);
		$this->assertTrue(count($d->getEvents()) === 0);
	}
	
	public function testClearShouldRemoveEventAndItsObservers()
	{
		$d = new Dispatcher();
		$d->attach('event', 'foo', 'test');
		$d->attach('event', 'bar', 'test');
		$this->assertTrue(count($d->getEvents()) === 1);
		$this->assertTrue($d->clear('event') === TRUE);
		$this->assertTrue(count($d->getEvents()) === 0);
	}
	
	public function testGetEventsShouldReturnArrayOfEventNames()
	{
		$d = new Dispatcher();
		$d->attach('event1', 'foo', 'test');
		$d->attach('event2', 'bar', 'test');
		$d->attach('event3', 'baz', 'test');
		foreach ($d->getEvents() as $event)
		{
			$this->assertTrue(in_array($event, array('event1', 'event2', 'event3'), true));
		}
	}
	
	public function testGetObserversShouldReturnObserversForAnEvent()
	{
		$d = new Dispatcher();
		$d->attach('event', 'foo', 'foo');
		$d->attach('event', 'bar', 'bar');
		$d->attach('event', 'zip', 'zip', array('key'=>'value'));
		
		$array = array( 0 => array('foo', 'foo', array()),
						1 => array('bar', 'bar', array()),
						2 => array('zip', 'zip', array('key'=>'value'))	
		);
		
		$this->assertEquals($array, $d->getObservers('event'));
		
		// returns empty array if event doesnt exist
		$this->assertEquals(array(), $d->getObservers('noevent'));
	}
	
	/**
	 * @covers Dispatcher::notify
	 * @covers Dispatcher::invoke
	 */
	public function testNotifyShouldReturnDoublyLinkedList()
	{
		$d = new Dispatcher();
		$d->attach('event', 'Foo', 'foo_test');
		$list = $d->notify('event');
		$this->assertEquals(get_class($list), 'SplDoublyLinkedList');
	}
	
	/**
	 * Also instantiates the class through invoke if context is sting.
	 * 
	 * @covers Dispatcher::notify
	 * @covers Dispatcher::invoke
	 */
	public function testNotifyShouldInstantiateClass()
	{
		$d = new Dispatcher();
		$d->attach('event', 'Foo', 'foo_test');
		$list = $d->notify('event');
	
		// Foo::foo_test returns foo
		$this->assertEquals($list[0], 'foo');
	}
	
	/**
	 * @covers Dispatcher::notify
	 * @covers Dispatcher::invoke
	 */
	public function testNotifyShouldInstantiateClassWithConfigurationArray()
	{
		$d = new Dispatcher();
		$d->attach('event', 'Constructor', 'getParam', array('param' => 'TEST'));
		$list = $d->notify('event');
	
		// Foo::foo_test returns foo
		$this->assertEquals($list[0], 'TEST');
	}
	
	/** 
	 * @covers Dispatcher::notify
	 * @covers Dispatcher::invoke
	 */
	public function testNotifyShouldAcceptUpToSixArguments()
	{
		$d = new Dispatcher();
		$d->attach('event', 'Bar', 'bar_test');
		$list = $d->notify('event', 1,2,3,4,5,6);
		$this->assertEquals($list[0], '123456');
		
		// should return false if more than 6 arguments provided
		$list = $d->notify('event', 1,2,3,4,5,6,7,8,9);
		$this->assertTrue($list[0] === false);
	}
	
	/** 
	 * @covers Dispatcher::notify
	 * @covers Dispatcher::invoke
	 */
	public function testNotifyShouldStopUponFalseReturnValueFromAnObserver()
	{
		$d = new Dispatcher();
		$d->attach('event', 'foo', 'foo_test'); // first
		$d->attach('event', new \Foo(), 'foo_test'); // second
		$d->attach('event', 'bar', 'testFalseReturn'); // third - false - stops
		
		// not processed because false returned previously
		$d->attach('event', new \Foo(), 'foo_test');
		
		$list = $d->notify('event');
		$this->assertEquals(3, $list->count());
		$this->assertEquals('foo', $list[0]);
		$this->assertEquals('foo', $list[1]);
		$this->assertEquals(false, $list[2]);
		$this->assertFalse($list->top());
	}

	/** 
	 * @covers Dispatcher::notify
	 * @covers Dispatcher::invoke
	 */
	public function testNotifyShouldInvokeStaticMethods()
	{
		$d = new Dispatcher();
		$d->attach('event', 'Foo', 'foo_static');
		$list = $d->notify('event');
		$this->assertEquals($list[0], 'static');
	}
	
	/** 
	 * @covers Dispatcher::chain
	 * @covers Dispatcher::invoke
	 */
	public function testChainShouldPassReturnToNextAsArgument()
	{
		$d = new Dispatcher();
		$d->attach('event', 'Foo', 'foo_chain'); // return foo_$value
		$d->attach('event', 'Bar', 'bar_chain'); // return bar_$value
		
		$this->assertEquals($d->chain('event', 'value'), 'bar_foo_value');
	}
}