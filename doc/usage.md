Usage
=====

All examples assumes the autoloading for Stampie is set up correctly.

Sending a message
-----------------

Sending a message consists of two parts. One is to create the message that is to be sent
and the other is to create a identity for when you need an email ex. for a receipient, sender etc.

An identity is just helper object that contains an email and a optional name.

Creating one looks like this:

``` php
<?php

use Stampie\Message\Identity;

$identity = new Identity('henrik@bjrnskov.dk', 'optional name here');
```

Stampie provides a immutable `Message` class that can be used for very simple messages.
But it is a good idea to create a `MessageInterface` implementation for every kind of
message you are going to send.

When using the immutable `Message` class the only required parameter in its constructor is `$from`.

``` php
<?php

use Stampie\Message\Identity;
use Stampie\Message\Message;

$headers = array();
$from = new Identity('henrik@bjrnskov.dk', 'Henrik Bjornskov');
$message = new Message($from, 'subject', 'html', 'text', $headers);
```

### The actual sending

Before sending anything a `Mailer` is needed. A mailer needs an `Adapter` for HTTP request and a `Handler`
which defines what provider that is used. There is a different handler for every provider because they
have vastly different apis.

Every handler takes an Adapter as its first constructor argument and a api key as the second.

Putting it all together makes it look like this:

``` php
<?php

use Stampie\Mailer;
use Stampie\Adapter\BuzzAdapter;
use Stampie\Handler\PostmarkHandler;

// .. create a Buzz Browser and a EventDispatcher instance.

$adapter = new BuzzAdapter($buzz);
$handler = new PostmarkHandler($adapter, 'api-key');
$mailer = new Mailer($handler, $dispatcher);

// create the Message as described earlier.

$to = new Identity('henrik@bjrnskov.dk');
$mailer->send($to, $message);
```

The different handlers are described later in the documentation.

### When it goes wrong

Something about the exceptions that is thrown after the sending of the message.
