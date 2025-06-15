<?php

declare(strict_types=1);

use StupidCMS\Core\Application;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

// Load environment
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Create and run application
$app = new Application();
$app->run();
