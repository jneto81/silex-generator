<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

use Silex\Application;

$app = new Silex\Application();
$app['debug'] = true;
$app['root_dir'] = __DIR__ . '/../';

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Samir\Provider\ServiceConfigServiceProvider(__DIR__ . "/../src/Samuca/Fashion/Resources/config/config.yml"));

$app->register(new Samir\Provider\RoutingConfigServiceProvider(__DIR__ . "/../src/Samuca/Fashion/Resources/config/routing.yml"));

$app->run();