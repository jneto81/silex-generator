<?php

namespace Samir\Provider;

use Silex\Application;

class ServiceConfigServiceProvider extends ConfigurationServiceProvider
{
  public function register(Application $app)
  {
    foreach ($this->readConfig() as $key => $value) {
      if ( ! empty($value)) {
        array_walk_recursive($value, function (&$value, $key) use ($app) {
          $matches = array();

          $value = preg_replace_callback('/\%(\w+)\%/', function ($matches) use ($app) {
            return $app[$matches[1]];
          }, $value);
          /*
          $matches = array();

          preg_match('/^\!(.*)/', $value, $matches);
          
          if (isset($matches[1])) {
            $service = $matches[1];
            
            $value = $app->share(function () use ($app, $service) {              
              return new $service($app);
            });
          }
          */
        });
      } else {
        $value = array();
      }
      
      $app->register(new $key(), $value);
    }
  }

  public function boot(Application $app)
  {
    
  }
}