# Stampie 

[![Build Status](https://secure.travis-ci.org/henrikbjorn/Stampie.png)](http://travis-ci.org/henrikbjorn/Stampie)

This is Stampie a WebService consumer for Email REST API's. It is dead simple and stupid.

## Supported companies

* SendGrid
* PostMark

## Supported http libraries

* Buzz
* Guzzle

## Usage

``` php
<?php

class Message extends \Stampie\Message
{
	public function getFrom() { return 'your@email.com'; }
	public function getSubject() { return 'You subject'; }
}

$message = new Message();
$message->setText('text');
$message->setHtml('html');

$mailer = new \Stampie\Mailer\Postmark(new \Stampie\Adapter\Buzz(new \Buzz\Browser()), 'ServerToken');
$mailer->send($message);
```
