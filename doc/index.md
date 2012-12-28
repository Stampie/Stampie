Stampie
=======

Stampie is a mailer library that makes it easy to use cloud providers such as:

* [Mandrill](http://mandrill.com)
* [Postmark](http://postmarkapp.com)
* [MailGun](http://mailgun.net)
* [SendGrid](http://sendgrid.com)

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

* [Usage](usage.md)
    * [Sending a message](usage.md#sending-a-message)
        * [The actual sending](usage.md#the-actual-sending)
        * [When it goes wrong](usage.md#when-it-goes-wrong)
    * [Providers](usage.md#providers)
        * [Postmark](usage.md#postmark)
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
