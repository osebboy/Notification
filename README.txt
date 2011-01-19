Notification v 2.0: Event based notification system for PHP
-----------------------------------------------------------

"Define a one-to-many dependency between objects so that when one object changes
state, all its dependents are notified and updated automatically."
- Design Patterns: Elements of Reusable Object Oriented Software, GOF, p.293

This package presents an implementation of Observer design pattern with lazy loading. 
Unlike other 'event dispatcher' implementations, this package DOES NOT use call_user* 
functions. It's based on straight method calls with a simple API. Notification v 2.0 
includes a Message class in case an Event or a Map type of container is needed. 

Some noticable features are:
1- Lazy loading of observers with an option to configure class instantiation
2- Does not use call_user_* functions on notification, only straight method calls
3- Does not have a defined event class and lets you pass on any number of arguments
4- Offers a class level (Dispatcher) as well as an application level (StaticDispatcher) 
notification system

Basic usage:

use Notification\Dispatcher;

$dispatcher = new Dispatcher();
$dispatcher->attach('eventName', 'classORobject', 'method');
$dispatcher->notify('eventName', 'argument1', 'argument2'...);


Notification package will help you creating loosely coupled objects focusing on the separation of concerns. I will be updating this package with new additions.
