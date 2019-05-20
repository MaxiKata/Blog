<?php

// Loads Composer autoload
require_once '../vendor/autoload.php';


// Use library debugger
use Tracy\Debugger;

Debugger::enable();

require_once '../router.php';
use Blog\Router;

$all = new Router();
$all ->run();