<?php
namespace Samir\Provider;

use Silex\ServiceProviderInterface;
use Silex\Application;

/**
 * @author 
 */
class AuthenticationServiceProvider implements ServiceProviderInterface 
{

	/**
	 * @param Application $app
	 */
	public function register(Application $app) 
	{
		$app->register(new \Samir\Provider\ServiceConfigServiceProvider(__DIR__ . '/Resources/config/config.yml'));
		$app->get($app['auth.login'], self::appLogin);
	}

	/**
	 * @param Application $app
	 */
	public function boot(Application $app) 
	{

	}
	

	public static function appShare()
	{
		global $app;
		
		return $app->share(function() use ($app) {
			return new \Samir\Provider\AuthenticationService\UserProvider($app['db']);
		});
	}
	
	public static function appLogin()
	{
		return $app['twig']->render('view\login.html.twig', array(
				'error' => $app['security.last_error']($request),
				'last_username' => $app['session']->get('_security.last_username')
		));
	} 
}