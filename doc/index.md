Stampie
=======

Installation
------------

Stampie can easily be installed with Composer.

```
composer require stampie/stampie [kriswallsmith/buzz]
```

A phar file is also provided. The phar file contains a autoloading stub which
is enabled when requiring the phar file.

``` php
<?php

require 'stampie.phar';

// Normal usage of Stampie
```

Requirements
------------

Stampie is dependent on a HTTP library such as Buzz. It is easy to integrate with
a custom HTTP library if needed.

Adapters:

* Buzz

Documentation
-------------

Not written yet.

License
-------

Stampie is released under the MIT license. The license can be found in the `LICENSE` file
and is distributed with every package of Stampie.
