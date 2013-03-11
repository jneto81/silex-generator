<?php

namespace Samir\Provider;

use Silex\Application;

class ServiceConfigServiceProvider extends ConfigurationServiceProvider
{
  public function register(Application $app)
  {
  	foreach ($this->readConfig() as $key => $value) {
    	array_walk_recursive($value, function (&$value) use ($app) {
    		
    		if (strpos($value, '%root_dir%') !== FALSE) {
    			$value = str_replace('%root_dir%', $app['root_dir'], $value);
    		}
    		
    	});

    	$app->register(new $key(), $value);
    }
  }

  public function boot(Application $app)
  {
    
  }
}