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
 * Dispatcher.
 * 
 * Simply works on an instance of Subject class. The subject instance can be
 * changed with Dispatcher::setInstance($subject) method. This gives flexibility
 * to create a subject class and make it available to the application on demand.
 * 
 * @author     Omercan Sebboy (www.osebboy.com)
 * @package    Notification
 * @copyright  Copyright(c) 2010, Omercan Sebboy (osebboy@gmail.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    1.0
 */
class Dispatcher
{
	/**
	 * Subject instance.
	 * 
	 * @var Subject
	 */
	protected static $instance = null;
	
	/**
	 * Get a Subject instance.
	 * 
	 * @return Subject 
	 */
	public static function getInstance()
    {
        if (null === self::$instance) 
        {
            self::setInstance(new Subject());
        }
        return self::$instance;
    }
    
    /**
     * Set the subject instance.
     * 
     * @param Subject $subject
     */
	public static function setInstance(Subject $subject)
    {
        self::$instance = $subject;
    }
    
	/**
	 * Connect event to an observer.
	 * (@see Notification\Handler)
	 * 
	 * @param string   $event   | event to observe
	 * @param mixed    $context | object or string class name
	 * @param string   $method  | method to call
	 * @param array    $config  | key, value pairs to use on class initialization
	 * @return Handler $handler | to detach later if needed
	 */
    public static function attach($event, $context, $method, $config = array())
    {
    	return self::getInstance()->attach($event, $context, $method, $config);
    }
    
    /**
	 * Remove handler.
	 * 
	 * @param \Notification\Handler $handler
	 * @return bool | true if detached.
	 */
    public static function detach(Handler $handler)
    {
    	return self::getInstance()->detach($handler);
    }
    
    /**
	 * Dispatch an event with arguments to the connected observers.
	 * 
	 * @param  string $event
	 * @param  mixed  $arguments
	 * @return mixed
	 */
    public static function notify($event, $arguments = null)
    {
    	return self::getInstance()->notify($event, $arguments);
    }
    
	/**
	 * Dispatch all connected events with arguments.
	 * 
	 * @param  mixed $arguments
	 * @return mixed
	 */
    public static function notifyAll($arguments = null)
    {
    	return self::getInstance()->notifyAll($arguments);
    }
    
    /**
	 * Dispatch until the return value from an observer is $untilValue.
	 * 
	 * Ex: Dispatcher::dispatchUntil($event, false, $argument1, $argument2); 
	 * 
	 * @param  string $event
	 * @param  mixed  $untilValue | bool most of the time
	 * @param  mixed  $arguments
	 * @return mixed
	 */
    public static function notifyUntil($event, $untilValue, $arguments = null)
    {
    	return self::getInstance()->notifyUntil($event, $untilValue, $arguments);
    }

    /**
     * Get handlers for an event.
     * 
     * @param string $event
     */
    public static function getHandlers($event)
    {
    	return self::getInstance()->getHandlers($event);
    }
    
    /**
     * Get subject's events.
     * 
     * @return array of events
     */
    public static function getEvents()
    {
    	return self::getInstance()->getEvents();
    }
    
    /**
     * Does the subject have $event?
     * 
     * @param  string $event
     * @return boolean
     */
    public static function hasEvent($event)
    {
    	return self::getInstance()->hasEvent($event);
    }
    
    /**
     * Reset an event.
     * 
     * @param  string $event
     * @return bool | true if successful
     */
    public static function reset($event)
    {
    	return self::getInstance()->reset($event);
    }
}
?>