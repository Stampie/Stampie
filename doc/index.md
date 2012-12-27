Stampie
=======

Stampie is a Mailer library that makes it easy to use cloud providers such as:

* Mandrill
* Postmark
* MailGun
* SendGrid

Installation
------------

Stampie can easily be installed with Composer.

```
composer require stampie/stampie [kriswallsmith/buzz]
```

A phar file is also provided. The phar file contains a autoloading stub which
is enabled when requiring the phar file.

``` php
<?php

require 'stampie.phar';
```

Requirements
------------

* [EventDispatcher](http://symfony.com/doc/current/components/event_dispatcher/)

Stampie is dependent on a HTTP library such as Buzz. It is easy to integrate with
a custom HTTP library if needed.

Adapters:

* [Buzz](http://github.com/kriswallsmith/buzz)

Documentation
-------------

* Usage
    * Sending a message
        * Postmark
        * Mandrill
        * MailGun
        * SendGrid
    * Spool messages
    * Enable logging
    * Hijack emails for development
* Extending
    * Add a Handler
    * Add a Adapter

License
-------

Stampie is released under the MIT license. The license can be found in the `LICENSE` file
and is distributed with every package of Stampie.
