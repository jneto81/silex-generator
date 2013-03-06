<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\MonologServiceProvider;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;

$app = new Silex\Application();

$app->register(new Samir\Silex\Provider\ConfigurationServiceProvider(), array(
	'conf' => {{ bundle_path }} . '/Resources/config/config.yml',
	'conf.routing' => {{ bundle_path }} . '/Resources/config/routing.yml'
));

$app->run();