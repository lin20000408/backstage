<?php

$hash = '$2y$10$GAdGbKSnl2O2Eig2fCWGBupJsgFKUt4qEKFYcy9eza2SSDtxhnsba';
$pw = '4514';
var_dump(password_verify($pw, $hash));