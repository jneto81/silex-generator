<?php

namespace Samir\Provider;

use Silex\Application;

class ServiceConfigServiceProvider extends ConfigurationServiceProvider
{
  public function register(Application $app)
  {
    foreach ($this->readConfig() as $controller => $routes) {
      $controller_prefix = $this->getControllerPrefix();
      
      $app[$controller_prefix . '.controller'] = $app->share(function () use ($app, $controller) {
        return new $controller());
      });
      
      foreach ($routes as $route_name_prefix => $settings) {
        $settings = (object)$settings;
        $method = strtolower($settings->method);
        $app->$method($settings->pattern, $controller_prefix . ':' . $settings->action . 'Action')
          ->bind($route_name_prefix);
      }
    }
  }

  public function boot(Application $app)
  {    
  }
  
  private function getControllerPrefix($controller)
  {
     $namespace = explode('\\', $controller);
     return strtolower(strstr(end($namespace), "Controller", true));
  }
}