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
 * @package    Notification
 * @copyright  Copyright(c) 2010 - 2011, Omercan Sebboy (osebboy@gmail.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    2.0
 */
namespace Notification;

use IteratorAggregate, ArrayIterator, ArrayAccess;

/**
 * A simple message container which can be utilized as an Event or as a map holding 
 * key - value pairs.
 * 
 * This class is not required in Notification system but can be used as a base class
 * for messages, events... etc. 
 * 
 * @author  Omercan Sebboy (www.osebboy.com)
 * @version 2.0
 */
class Message implements IteratorAggregate, ArrayAccess
{
	/**
	 * Message title.
	 * 
	 * @var string
	 */
	protected $title;
	
	/**
	 * Message body.
	 * 
	 * @var array
	 */
	protected $body = array();
	
	/**
	 * Construct a message with a title and body.
	 * 
	 * @param string $title
	 * @param array $body
	 */
	public function __construct($title, array $body = array())
	{
		$this->title = (string) $title;
		$this->body  = $body;
	}
	
	/**
	 * Get message body.
	 * 
	 * @return array
	 */
	public function getBody()
	{
		return $this->body;
	}
	
	/**
	 * Iterator per IteratorAggregate interface.
	 * 
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->body);
	}
	
	/**
	 * Get message title.
	 * 
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Append value to message body.
	 * 
	 * @param mixed $value
	 * @return void
	 */
	public function insert($value)
	{
		$this->body[] = $value;
	}
	
	/**
	 * Checks whether the message body is empty.
	 * 
	 * @return boolean.
	 */
	public function isEmpty()
	{
		return !$this->body;
	}
	
	/**
	 * Get value with a key per ArrayAccess interface.
	 * 
	 * @param mixed $key | integer or string
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return isset($this->body[$key]) ? $this->body[$key] : null;
	}
	
	/**
	 * Tests whether key exists in message body per ArrayAccess interface.
	 * 
	 * @param mixed $key | integer or string
	 * @return boolean
	 */
	public function offsetExists($key)
	{
		return isset($this->body[$key]);
	}

	/**
	 * Set value with a key per ArrayAccess interface.
	 * 
	 * @param mixed $key | integer or string
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		$key ? $this->body[$key] = $value : $this->body[] = $value;
	}

	/**
	 * Erase value with key per ArrayAccess interface.
	 * 
	 * @param mixed $key | integer or string
	 * @return void
	 */
	public function offsetUnset($key)
	{
		unset($this->body[$key]);
	}
	
	/**
	 * Message body can have the same value more than once. This method
	 * removes all of them and returns number of removals.
	 * 
	 * @param $value
	 * @return integer
	 */
	public function remove($value)
	{
		$keys = array_keys($this->body, $value, true);
		foreach ($keys as $key)
		{
			unset($this->body[$key]);
		}
		return count($keys);
	}
	
	/**
	 * Returns the number of elements in the message body.
	 * 
	 * @return integer
	 */
	public function size()
	{
		return count($this->body);
	}
}