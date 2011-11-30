# Stampie

[![Build Status](https://secure.travis-ci.org/henrikbjorn/Stampie.png)](http://travis-ci.org/henrikbjorn/Stampie)

Stampie is a simple API Wrapper for different email providers such as [Postmark](http://postmarkapp.com) and [SendGrid](http://sendgrid.com).

It is very easy to use and to integrate into your application as demonstrated below with a `SendGrid` mailer.

``` php
<?php

class Message extends \Stampie\Message
{
	public function getFrom() { return 'alias@domain.tld'; }
	public function getSubject() { return 'You are trying out Stampie'; }
	public function getText() { return 'So what do you think about it?'; }
}

$adapter = new Stampie\Adapter\Buzz(new Buzz\Browser());
$mailer = new Stampie\Mailer\SendGrid($adapter, 'username:password');

// Returns Boolean true on success or throws an HttpException for error
// messages not recognized by SendGrid api or ApiException for known errors.
$mailer->send(new Message('reciever@domain.tld'));
```

This simple example shows a few different things about how Stampis works under the hood and is developed. Because others are **so much** better than us to do Http communication Stampie uses a [Adapter Pattern](http://en.wikipedia.org/wiki/Adapter_pattern) to integrate with known libraries such as [Buzz](http://github.com/kriswallsmith/Buzz) and [Guzzle](http://guzzlephp.org).

Every mailer takes a `$serverToken` as the second argument in their constructor. This is what is used for authentication. In the Postmark mailer this is a hash but in SendGrid it is a `username:password` pattern that is split into two pieces and send as arguments. A Mailer is responsible for formatting the request needed for a given API.

A `Message` or `MessageInterface` is a simple storage class that holds information about the message sent to an API such as the email address this is from and who should recieve it together with html and text bodies.

Last their is an Interface for every type of class or abstract implementation that should be used when adding new Mailer's or Adapter's.

## Framework integration

Their is currently no integration to any framework and the will not be any framework specific classes or usage of in this library. Any integration will in the future be provided in framework specific plugins in their own repositories.

Currently only a [Symfony2](http://symfony.com) bundle is planned.

## Testing

Stampie is [Continuous Integration](http://en.wikipedia.org/wiki/Continuous_integration) tested with [Travis](http://travis-ci.org) and aims for a high coverage percentage.

## Developing

As mentioned above if integrating new mailers or adapters please rely on the interfaces or abstract classes already in this package. Furthermore unit tests should be provided aswell.


## Feedback

This is a project created to test TDD along the way and maybe have some scars from that. But you are always welcome to send feedback or Github, Twitter, Github issue or Pull Request. Same goes if something is wrong or you have ideas for a better or smarter implementation.
