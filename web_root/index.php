<?php

use Core\Router;

$appRoot = '../app';
$coreRoot = '../core';

require($coreRoot.'/Router.php');
require($coreRoot.'/AbstractController.php');
require($coreRoot.'/AbstractRepository.php');
require($coreRoot.'/InputParsing.php');

require($appRoot.'/config.php');
require($appRoot.'/model/load.php');

$router = new Router($appRoot);

$router->loadComponentRoutes();
$router->run();

exit();