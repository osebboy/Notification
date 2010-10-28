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
 * This class holds the observer class definition that is set when 
 * Subject's attach() method is called. Class construct accepts a configuration
 * array as the last argument providing a flexible class instantiation.
 * 
 * @author     Omercan Sebboy (www.osebboy.com)
 * @package    Notification
 * @copyright  Copyright(c) 2010, Omercan Sebboy (osebboy@gmail.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    1.0
 */
class Handler
{
/**
	 * Handled event.
	 * 
	 * @var string
	 */
	protected $event;
	
	/**
	 * Observer class name or object.
	 * 
	 * @var mixed
	 */
	protected $context;
	
	/**
	 * Method name to call in observer class.
	 * 
	 * @var string
	 */
	protected $method;
	
	/**
	 * A config array to use on observer class's initialization.
	 * (@see getConfig() for more information)
	 * 
	 * @var array
	 */
	protected $config = array();
	
	/**
	 * Construct.
	 * 
	 * @param string $event   | event name
	 * @param string $context | class name(string) or object
	 * @param string $method  | method to call
	 * @param array  $config  | array to use on class initialization
	 */
	public function __construct($event, $context, $method, array $config = array())
	{
		$this->event 	= $event;
		$this->context  = $context;
		$this->method   = $method;
		$this->config   = $config;
	}
	
	/**
	 * Get event name.
	 * 
	 * @return array
	 */
	public function getEvent()
	{
		return $this->event;
	}
	
	/**
	 * Get object. If context is class name, then creates an instance.
	 * 
	 * @return object
	 */
	public function getObject()
	{
		$context = $this->context;
		if (is_string($context))
		{
			// if context is string, then it's class name. Instantiate it.
			if (!class_exists($context))
			{
				throw new \Notification\Exception('Class does not exist: ' . $context);
			}
			$config  = $this->getConfig();
			$context = !empty($config) ? new $context($config) : new $context();
		}
		if (!is_object($context))
		{
			throw new \Notification\Exception('Context can either be a class name or an object.');
		}
		return $context;
	}
	
	/**
	 * Get method name.
	 * 
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}
	
	/**
	 * Get observer's constructor config array.
	 * This gives the flexibility to initiate an observer with
	 * default values before the event is handled. For this to 
	 * be used, the observer class construct has to accept a
	 * config array.
	 * 
	 * @return array
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Invoke observer class's method with incoming arguments.
	 * 
	 * Straight method calls are much faster (at least 2 times) than 
	 * using call_user_func or call_user_func_array.
	 * 
	 * @param  array $args
	 * @return mixed | false if cannot invoke the method
	 */
	public function handle(array $args = array())
	{
		$method = $this->getMethod();
		$object = $this->getObject();

		if (!method_exists($object, $method))
		{
			throw new \Notification\Exception('Method does not exist: ' . $method);
		}

		switch (count($args)) {
			case 0:
				return $object->{$method}();
			case 1:
				return $object->{$method}($args[0]);
			case 2:
				return $object->{$method}($args[0], $args[1]);
			case 3:
				return $object->{$method}($args[0], $args[1], $args[2]);
			case 4:
				return $object->{$method}($args[0], $args[1], $args[2], $args[3]);
			case 5:
				return $object->{$method}($args[0], $args[1], $args[2], $args[3], $args[4]);
			case 6:
				return $object->{$method}($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
			default:
				return false;
		}
	}
}
?>