<?php

namespace Samir\Provider;

use Silex\Provider\FormServiceProvider;
use Silex\Application;

class FormServiceProviderImpl extends FormServiceProvider
{
  public function register(Application $app)
  {
    parent::register($app);
    
    $app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions) use ($app) {
      $managerRegistry = new \Samir\Persistance\ManagerRegistry(null, array(), array('db.orm.em'), null, null, $app['db.orm.proxies_namespace']);
      $managerRegistry->setContainer($app);
      $extensions[] = new \Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension($managerRegistry);
   
      return $extensions;
    }));
  }
}

