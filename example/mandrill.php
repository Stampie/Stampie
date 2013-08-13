<?php

use Stampie\Handler\MandrillHandler;

function get_handler($adapter, $key) {
    return new MandrillHandler($adapter, $key);
}

require __DIR__ . '/bootstrap.php';
