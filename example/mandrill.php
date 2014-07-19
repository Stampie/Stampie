<?php

use Stampie\Carrier\MandrillCarrier;

function get_carrier($key) {
    return new MandrillCarrier($key);
}

require __DIR__ . '/bootstrap.php';
