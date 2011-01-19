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

use Notification\Message;

require_once __DIR__ . '/../TestHelper.php';

/**
 * Message Test
 * 
 * 
 */
class MessageTest extends \PHPUnit_Framework_TestCase 
{
	protected function setUp() 
	{
		$this->message = new Message('test');
	}

	protected function tearDown() 
	{
	}

	public function testGetTitle()
	{
		$this->assertEquals('test', $this->message->getTitle());
	}
	
	public function testGetBody()
	{
		$this->message->insert('value');
		$this->assertEquals(array('value'), $this->message->getBody());
	}
	
	public function testInsert()
	{
		$this->message->insert('foo');
		$this->message->insert('bar');
		$this->message->insert('zip');
		$this->assertEquals(3, $this->message->size());
	}
	
	public function testIsEmpty()
	{
		$this->assertTrue($this->message->isEmpty() === true);
	}
	
	public function testRemove()
	{
		$this->message->insert('one');
		$this->message->insert('two');
		$this->message->insert('three');
		$this->message->insert('one');
		$this->message->insert('one');
		
		// remove should remove all 'one' s
		$this->assertEquals(3, $this->message->remove('one'));
		
		// after removal, only 2 should be left 
		$this->assertEquals(2, $this->message->size());
	}
	
	public function testSize()
	{
		$this->assertEquals(0, $this->message->size());
	}
	
	public function testOffsetGet()
	{
		$this->message['test'] = 'value';
		$this->assertEquals('value', $this->message->offsetGet('test'));
	}
	
	public function testOffsetExists()
	{
		$this->message['test'] = 'value';
		$this->assertTrue(isset($this->message['test']));
		$this->assertTrue($this->message->offsetExists('test'));
	}
	
	public function testOffsetSet()
	{
		$this->message['one'] = 'value1';
		$this->assertEquals('value1', $this->message->offsetGet('one'));
		
		$this->message[] = 'integer';
		$this->assertEquals('integer', $this->message->offsetGet(0));
		
		$this->message->offsetSet('two', 'value2');
		$this->assertEquals('value2', $this->message->offsetGet('two'));	
		$this->assertEquals(3, $this->message->size());	
	}
	
	public function testOffsetUnset()
	{
		$this->message['one'] = 'value';
		unset($this->message['one']);
		$this->assertTrue($this->message->isEmpty());
		
		$this->message['two'] = 'two';
		$this->message->offsetUnset('two');
		$this->assertTrue($this->message->isEmpty());
	}
}
?>