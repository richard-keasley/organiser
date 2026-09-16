<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../app/Config/App.php';
$routes = require __DIR__ . '/../app/Config/Routes.php';

$paths = require __DIR__ . '/../system/bootstrap.php';

$bootstrap = \CodeIgniter\CodeIgniter::createApplication($paths, $app, $routes);
$bootstrap->run();
