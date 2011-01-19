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

/**
 * To run the tests, simply point to Tests dir on the command line: 
 * phpunit test/Notification/Tests
 */
require_once 'PHPUnit\Framework\TestCase.php';

function autoload($class)
{
	require __DIR__ . '/../../src/' . str_replace('\\', '/', ltrim($class)) . '.php';
}
spl_autoload_register('autoload');

/**
 * Helper classes for testing.
 */
class Constructor
{
	protected $param;
	
	public function __construct(array $config = array())
	{
		!$config ? : $this->setConfig($config);
	}
	
	public function setParam($value)
	{
		$this->param = $value;
	}
	
	public function getParam()
	{
		return $this->param;
	}
	
	public function setConfig(array $config)
	{
		foreach ($config as $key => $val)
        {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
            {
                $this->{$method}($val);
            }          
        }
	}
}

class Foo
{
	public function foo_test()
	{
		return 'foo';
	}
	
	public function foo_multiple($arg1, $arg2, $arg3)
	{
		return 'foo_' . $arg1 . $arg2 . $arg3;
	}
	
	public function foo_chain($argument)
	{
		return "foo_$argument";
	}
	
	public static function foo_static()
	{
		return 'static';
	}
}

class Bar
{
	public function bar_test($one, $two, $three, $four, $five, $six)
	{
		return $one . $two . $three . $four . $five . $six;
	}
	
	public function bar_chain($argument)
	{
		return "bar_$argument";
	}
	
	public function testFalseReturn()
	{
		return false;
	}
}