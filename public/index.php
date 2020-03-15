<?php

require __DIR__ . '/../src/boot.php';

use Http\Request;

$request = Request::createFromGlobals();

$kernel = new Kernel();
$kernel->handle($request);
