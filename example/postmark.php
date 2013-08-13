<?php

use Stampie\Handler\PostmarkHandler;

function get_handler($adapter, $key) {
    return new PostmarkHandler($adapter, $key);
}

require __DIR__ . '/bootstrap.php';
