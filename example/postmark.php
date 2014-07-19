<?php

use Stampie\Carrier\PostmarkCarrier;

function get_carrier($adapter, $key) {
    return new PostmarkCarrier($adapter, $key);
}

require __DIR__ . '/bootstrap.php';
