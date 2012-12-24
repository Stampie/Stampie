Stampie2
========

Pure Stampie:

``` php
<?php
use Buzz\Browser;
use Buzz\Client\Curl;
use Stampie\Adapter\BuzzAdapter;
use Stampie\Handler\PostmarkHandler;
use Stampie\Identity;
use Stampie\Mailer;
use Stampie\Message\Message;

$adapter = new BuzzAdapter(new Browser(new Curl));
$handler = new PostmarkHandler($adapter, 'my-api-key-here');

$mailer = new Mailer($handler);
$mailer->send(new Identity('henrik@bjrnskov.dk', 'Henrik Bjornskov'), new Message());
```

Silex:

``` php
<?php

$message = new Stampie\Message\Message();

$app['stampie']->send('henrik@bjrnskov.dk', $message);
```

Symfony:

``` php
<?php

$message = new Stampie\Message\Message();

$container->get('stampie')->send('henrik@bjrnskov.dk', $message);
```
