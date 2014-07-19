<?php

use Stampie\Carrier\PostmarkCarrier;

function get_carrier($key) {
    return new PostmarkCarrier($key);
}

require __DIR__ . '/bootstrap.php';
