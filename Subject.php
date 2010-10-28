<?php
/**
 * Notification: Event based notification system for PHP
 * 
 * Copyright (c) 2010, Omercan Sebboy <osebboy@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Omercan Sebboy nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRIC
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author     Omercan Sebboy (www.osebboy.com)
 * @package    Notification
 * @copyright  Copyright(c) 2010, Omercan Sebboy (osebboy@gmail.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    1.0
 */
namespace Notification;

/**
 * Subject.
 * 
 * @author     Omercan Sebboy (www.osebboy.com)
 * @package    Notification
 * @copyright  Copyright(c) 2010, Omercan Sebboy (osebboy@gmail.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    1.0
 */
class Subject
{
	/**
	 * Events.
	 * 
	 * @var array of string event names and its associated handlers
	 */
	protected $events = array();

	/**
	 * Attach observer (the observer's class definition).
	 * (@see Notification\Handler)
	 * 
	 * @param string   $event   | event to observe
	 * @param mixed    $context | object or string class name
	 * @param string   $method  | method to call
	 * @param array    $config  | key, value pairs to use on class initialization
	 * @return Handler $handler | to detach later if needed
	 */
	public function attach($event, $context, $method, $config = array())
	{
		$event = strtolower($event);
		if (!isset($this->events[$event]))
		{
			$this->events[$event] = array();
		}
		$handler = new Handler($event, $context, $method, $config);
		$key 	 = spl_object_hash($handler);
		$this->events[$event][$key] = $handler;
		return $handler;
	}
	
	/**
	 * Remove handler.
	 * 
	 * @param \Notification\Handler $handler
	 * @return bool | true if detached.
	 */
	public function detach(Handler $handler)
	{
		$event = $handler->getEvent();
		$key   = spl_object_hash($handler);
		if (isset($this->events[$event][$key]))
		{
			unset($this->events[$event][$key]);
			return true;
		}
		return false;
	}
	
	/**
	 * Notify an event with arguments.
	 * 
	 * @param  string $event
	 * @param  mixed  $arguments
	 * @return mixed
	 */
	public function notify($event, $arguments = null)
	{
		if (!$this->hasHandlers($event))
		{
			return;
		}
		$returnValue = null;
		$arguments 	 = func_get_args();
		array_shift($arguments);
		foreach ($this->getHandlers($event) as $handler)
		{
			$returnValue = $handler->handle($arguments);
		}
		return $returnValue;
	}
	
	/**
	 * Notify all registered events with arguments.
	 * 
	 * @param  mixed $arguments
	 * @return mixed
	 */
	public function notifyAll($arguments = null)
	{
		if (empty($this->events))
		{
			return;
		}
		$handlers = array();
		$events   = $this->getEvents();
		foreach ($events as $event)
		{
			if ($this->hasHandlers($event))
			{
				$handlers = array_merge($handlers, $this->events[$event]);
			}
		}
		$returnValue = null;
		if (empty($handlers))
		{
			return $returnValue;
		}
		$arguments = func_get_args();
		foreach ($handlers as $handler)
		{
			$returnValue = $handler->handle($arguments);
		}
		return $returnValue;
	}
	
	/**
	 * Notify until the return value from a handler is $untilValue.
	 * 
	 * @param  string $event
	 * @param  mixed  $untilValue | bool most of the time
	 * @param  mixed  $arguments
	 * @return mixed
	 */
	public function notifyUntil($event, $untilValue, $arguments = null)
	{
		if (!$this->hasHandlers($event))
		{
			return;
		}
		$returnValue = null;
		$arguments   = func_get_args();
		$arguments 	 = array_slice($arguments, 2);
        foreach ($this->getHandlers($event) as $handler) 
        {
        	$returnValue = $handler->handle($arguments);
       		if ($returnValue === $untilValue)
       		{
       			break;
       		}
        }
        return $returnValue;
	}

	/**
	 * Does event have handlers?
	 * 
	 * @param  string $event
	 * @return boolean
	 */
	public function hasHandlers($event)
	{
		if (!isset($this->events[$event]))
		{
			$this->events[$event] = array();
		}
		return (count($this->events[$event]) > 0);
	}

	/**
	 * Get handlers for an event.
	 * 
	 * @param  string $event
	 * @return array of handlers
	 */
	public function getHandlers($event)
	{
		if (!isset($this->events[$event]))
		{
			return array();
		}
		return $this->events[$event];
	}
	
	/**
	 * Get all events.
	 * 
	 * @return array
	 */
	public function getEvents()
	{
		return array_keys($this->events);
	}
	
	/**
	 * Is this subject have $event registered?
	 * 
	 * @param  string $event
	 * @return boolean
	 */
	public function hasEvent($event)
	{
		return isset($this->events[$event]);
	}
	
	/**
	 * Reset an event.
	 * 
	 * @param  string $event
	 * @return boolean | true if removed.
	 */
	public function reset($event)
	{
		if (isset($this->events[$event]))
		{
			unset($this->events[$event]);
			return true;
		}
		return false;
	}
}
?>