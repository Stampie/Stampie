<?php
Phar::mapPhar();

spl_autoload_register(function ($className) {
    if (0 !== strpos($className, 'Stampie\\')) {
        return false;
    }

    $file = 'phar://stampie.phar/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    if (file_exists($file)) {
        require $file;

        return true;
    }

    return false;
});

__HALT_COMPILER();
