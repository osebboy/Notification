Notification: Event based notification system for PHP
-----------------------------------------------------

"Define a one-to-many dependency between objects so that when one object changes
state, all its dependents are notified and updated automatically."
- Design Patterns: Elements of Reusable Object Oriented Software, GOF, p.293

This package presents an implementation of Observer design pattern with lazy loading. 
Unlike other 'event dispatcher' implementations, this package DOES NOT use call_user* 
functions. It's based on straight method calls with a simple API. 

Some noticable features are:
1- Lazy loading of observers with an option to configure class instantiation
2- Does not use call_user_* functions on notification, only straight method calls
3- Does not have a defined event class and lets you pass on any number of arguments
4- Offers a class level (Subject) as well as an application level (Dispatcher) notification system
5- 3 notification methods : notify(), notifyAll() and notifyUntil()

Basic usage:
$subject = new Subject();
$subject->attach('eventName', 'className', 'methodName', array('config'=>'array'));
...
$subject->notify('eventName', 'argument'...);
$subject->notifyAll('argument'...);
$subject->notifyUntil('eventName', false, 'argument'...);

Notification package will help you creating loosely coupled objects focusing on the 
separation of concerns. I will be updating this package with new additions.
See the blog post for an introduction.
http://www.osebboy.com/blog/event-based-notification-system-for-php/