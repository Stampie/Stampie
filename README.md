# Stampie

[![Build Status](https://travis-ci.org/Stampie/Stampie.svg?branch=master)](https://travis-ci.org/Stampie/Stampie)

Stampie is a simple API Wrapper for different email providers such as [Postmark](https://postmarkapp.com) and [SendGrid](https://sendgrid.com).

It is very easy to use and to integrate into your application as demonstrated below with a `SendGrid` mailer.

## Providers

* [Postmark](https://postmarkapp.com)
* [SendGrid](https://sendgrid.com)
* [MailGun](https://www.mailgun.com)
* [Mandrill](https://mandrill.com/)

``` php
<?php

// composer autoloading.
require 'vendor/autoload.php';

class Message extends \Stampie\Message
{
	public function getFrom() { return 'alias@domain.tld'; }
	public function getSubject() { return 'You are trying out Stampie'; }
	public function getText() { return 'So what do you think about it?'; }
}

$adapter = new Http\Adapter\Guzzle6\Client();
$mailer = new Stampie\Mailer\SendGrid($adapter, 'username:password');

// Returns Boolean true on success or throws an HttpException for error
// messages not recognized by SendGrid api or ApiException for known errors.
$mailer->send(new Message('reciever@domain.tld'));
```

This simple example shows a few different things about how Stampie works under the hood and is developed. Because others are **so much** better than us to do Http communication, Stampie uses the [HTTPlug](http://httplug.io/) abstraction so you are free to choose between any library like [Buzz](http://github.com/kriswallsmith/Buzz) or [Guzzle](http://guzzlephp.org). See the full list here: https://packagist.org/providers/php-http/client-implementation

Every mailer takes a `$serverToken` as the second argument in their constructor. This is what is used for authentication. In the Postmark mailer this is a hash but in SendGrid it is a `username:password` pattern that is split into two pieces and send as arguments. A Mailer is responsible for formatting the request needed for a given API.

A `Message` or `MessageInterface` is a simple storage class that holds information about the message sent to an API such as the email address this is from and who should recieve it together with html and text bodies.

Last their is an Interface for every type of class or abstract implementation that should be used when adding new Mailer's or Adapter's.

## Documentation

There is generated API documentation for all tags and released versions. Those can be found at [stampie.github.io/Stampie/api/master/](https://stampie.github.io/Stampie/api/master/).

## Extensions

* [StampieExtra](https://github.com/stof/StampieExtra) provides extensions
  to Stampie using the Symfony2 EventDispatcher component.

## Framework integration

Stampie is itself completly decoupled and does not depend on any framework.

### Integrations

* [HBStampieBundle](https://github.com/Stampie/HBStampieBundle) it is also [on packagist](https://packagist.org/packages/henrikbjorn/stampie-bundle)

## Testing

Stampie is [Continuous Integration](https://en.wikipedia.org/wiki/Continuous_integration) tested with [Travis](https://travis-ci.org) and aims for a high coverage percentage.

## Developing

As mentioned above if integrating new mailers or adapters please rely on the interfaces or abstract classes already in this package. Furthermore unit tests should be provided aswell.


## Feedback

This is a project created to test TDD along the way and maybe have some scars from that. But you are always welcome to send feedback or Github, Twitter, Github issue or Pull Request. Same goes if something is wrong or you have ideas for a better or smarter implementation.
