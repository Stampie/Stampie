# Stampie

[![CI](https://github.com/Stampie/Stampie/actions/workflows/ci.yml/badge.svg)](https://github.com/Stampie/Stampie/actions/workflows/ci.yml)

Stampie is a simple API Wrapper for different email providers such as [Postmark](https://postmarkapp.com) and [SendGrid](https://sendgrid.com).

It is very easy to use and to integrate into your application as demonstrated below with a `SendGrid` mailer.

## Providers

* [Postmark](https://postmarkapp.com)
* [SendGrid](https://sendgrid.com)
* [MailGun](https://www.mailgun.com)
* [Mandrill](https://mandrill.com/)
* [SparkPost](https://sparkpost.com)
* [Mailjet](https://www.mailjet.com)

```php
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

// Throws an HttpException for error
// messages not recognized by SendGrid api or ApiException for known errors.
$mailer->send(new Message('reciever@domain.tld'));
```

This simple example shows a few different things about how Stampie works under the hood and is developed. Because others
are **so much** better than us to do HTTP communication, Stampie uses the [HTTPlug](https://httplug.io/) abstraction so
you are free to choose between any library like [Buzz](https://github.com/kriswallsmith/Buzz) or [Guzzle](https://docs.guzzlephp.org).
See the full list here: https://packagist.org/providers/php-http/client-implementation

Every mailer takes a `$serverToken` as the second argument in their constructor. This is what is used for authentication.
In the Postmark mailer this is a hash but in SendGrid it is a `username:password` pattern that is split into two pieces
and send as arguments. A mailer is responsible for formatting the request needed for a given API.

A `Message` or `MessageInterface` is a simple storage class that holds information about the message sent to an API such 
as the email address this is from and who should receive it together with html and text bodies.

Last there is an Interface for every type of class or abstract implementation that should be used when adding new Mailer's 
or Adapter's.

## Installation

Stampie is not hard coupled to Guzzle or any other library that sends HTTP messages. It uses an abstraction 
called HTTPlug. This will give you the flexibility to choose what PSR-7 implementation and HTTP client to use. 

If you just want to get started quickly you should run the following command: 

```bash
composer require stampie/stampie php-http/curl-client php-http/message guzzlehttp/psr7
```

### Why requiring so many packages?

Stampie has a dependency on the virtual package [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) 
which requires to you install **an** adapter, but we do not care which one. That is an implementation detail in your application. 
We also need **a** PSR-7 implementation and **a** message factory. 

You do not have to use the `php-http/curl-client` if you do not want to. You may use the `php-http/guzzle6-adapter` or any
other library in [this list](https://packagist.org/providers/php-http/client-implementation). 
Read more about the virtual packages, why this is a good idea and about the flexibility it brings at the [HTTPlug docs](https://docs.php-http.org/en/latest/httplug/users.html).

## Documentation

There is generated API documentation for all tags and released versions. Those can be found at [stampie.github.io/Stampie/api/main/](https://stampie.github.io/Stampie/api/main/).

## Extensions

* [Stampie Extra](https://github.com/Stampie/extra) provides extensions to Stampie using the Symfony EventDispatcher component.

## Framework integration

Stampie is itself completely decoupled and does not depend on any framework.

### Integrations

* [StampieBundle](https://github.com/Stampie/stampie-bundle) it is also [on packagist](https://packagist.org/packages/stampie/stampie-bundle)

## Testing

Stampie is [Continuous Integration](https://en.wikipedia.org/wiki/Continuous_integration) tested with GitHub Actions and aims for a high coverage percentage.

## Developing

As mentioned above if integrating new mailers or adapters please rely on the interfaces or abstract classes already in this package. Furthermore unit tests should be provided as well.


## Feedback

This is a project created to test TDD along the way and maybe have some scars from that. But you are always welcome to send feedback or GitHub, Twitter, GitHub issue or Pull Request. Same goes if something is wrong or you have ideas for a better or smarter implementation.
