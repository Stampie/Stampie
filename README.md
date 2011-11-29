# Stampie 

This is Stampie a Postmark API Consumer. It is dead simple and stupid.

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
