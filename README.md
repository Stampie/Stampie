<p align="center">
    <img src="https://raw.github.com/henrikbjorn/Stampie/next-version/doc/logo.png" alt="Stampie" />
</p>


Stampie is a small library that makes it easy to send emails through online services. It does this by
implementing specific handlers for each of them.

Supported services are currently:

 * [Mandrill](https://www.mandrill.com/)
 * [Postmark](https://postmarkapp.com/)


Warning
-------

This branch aims to fix some of the messy stuff that have happended over time in the stable releases.
Together with integrating most of the features from StampieExtra. This makes it more feature complete
and enjoyable to work with.

This branch contains the next iteration of Stampie. This means all the features currently in the
stable version are not yet migrated over. Also this means the code here is extremely conceptional
and may not work as intended.

Documentation
--------------

### Setup

Stampie is made up of a couple of different things. The first is a "Carrier" the carrier is responsible
for creating and formatting a Message into a Request. The Request is then given to an Adapter which
responsibility is to actually do the call to the service. Theese are all bound together by the Mailer.

In order to setup you need a Mailer, an Adapter and a Carrier. The following uses Buzz as an example
but Stampie comes with an Adapter for Guzzle aswell.

``` php
<?php

use Buzz\Browser;
use Stampie\Adapter\BuzzAdapter;
use Stampie\Carrier\PostmarkCarrier;
use Stampie\Mailer;
use Symfony\Component\EventDispatcher\EventDispatcher;

$mailer = new Mailer(new PostmarkCarrier('my-api-key'), new BuzzAdapter(new Browser), new EventDispatcher);
```

### Sending Messages

Stampie comes with an implemetation of the Message interface called DefaultMessage. DefaultMessage makes it
easy to start sending out messages. DefaultMessage is a simple ValueObject and takes every possible argument
in its constructor.

It is important to know that a Message does not contain the recipient as it did in version 0.x.x. This is because
the recipient is almost always a dynamic value, and therefor it does not make sense to have it on the message.

The recipient is wrapped as an Identity. An Identity always consists of the recipient email and sometimes also the
name. If you use the name it will show up as "My Name <noreply@email.tld>" in your inbox.

``` php
<?php

use Stampie\Message\DefaultMessage;
use Stampie\Identity;

// assuming $mailer is the same as the previous example.
$from = new Identity('from@domain.tld', 'Optional From Name');

$message = new DefaultMessage($from, 'Subject', '<b>Html</b>', 'Text');

$mailer->send(new Identity('to@domain.tld', 'Optional To Name'), $message);
```

The send method returns a MessageHeader. The MessageHeader contains a identifier given by the carrier. This identifier
is different from carrier to carrier. Also if an event stopped the sending the identifier will be null.

### Running the Examples

Stampie comes with a example for each carrier. This will send a email through the carrier to the email you provide.

``` bash
$ php example/insert-carrier.php "my-api-key" "to@domain.tld" "from@domain.tld"
```
