# Stampie 

This is Stampie a Postmark API Consumer. It is dead simple and stupid.

## Usage

``` php
<?php

class Message implements \Stampie\MessageInterface
{
}

$mailer = new \Stampie\Mailer(new \Buzz\Browser());
$mailer->send(new Message());
```