#!/usr/bin/env bash

FILE="stub.php"
STUB="
<?php
Phar::mapPhar();

__HALT_COMPILER();
"

echo $STUB > $FILE
phar-build --src=lib -ns --phar=build/stampie.phar --stub=$FILE
rm $FILE
