<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\MonologServiceProvider;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;

$app = new Silex\Application();

$app->register(new Samir\Provider\ServiceConfigServiceProvider(__DIR__ . "{{ bundle }}/Resources/config/config.yml"));

$app->register(new Samir\Provider\RoutingConfigServiceProvider(__DIR__ . "{{ bundle }}/Resources/config/routing.yml"));

$app->run();