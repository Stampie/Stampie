<?php

use Stampie\Carrier\MandrillCarrier;

function get_carrier($adapter, $key) {
    return new MandrillCarrier($adapter, $key);
}

require __DIR__ . '/bootstrap.php';
