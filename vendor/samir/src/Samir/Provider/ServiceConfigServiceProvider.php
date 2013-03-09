<?php

namespace Samir\Provider;

use Silex\Application;

class ServiceConfigServiceProvider extends ConfigurationServiceProvider
{
  public function register(Application $app)
  {
    foreach ($this->readConfig() as $key => $value) {
      $app->register(new $key(), $value);
    }
  }

  public function boot(Application $app)
  {
    
  }
}