Stampie2
========

Pure Stampie:

``` php
<?php

$adapter = new Stampie\Adapter\BuzzAdapter($buzz);
$handler = new Stampie\Handler\MandrilHandler(API_KEY);
$mailer  = new Stampie\Mailer($handler, $adapter);

$message = new Stampie\Message\Message();

$mailer->send('hb@peytz.dk', $message);
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
