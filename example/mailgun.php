<?php

use Stampie\Carrier\MailGunCarrier;

function get_carrier($key) {
    return new MailGunCarrier($key);
}

require __DIR__ . '/bootstrap.php';
