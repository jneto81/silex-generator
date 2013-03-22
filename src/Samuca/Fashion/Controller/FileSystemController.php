<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;

class FileSystemController extends Controller
{
	/**
	 * @Route("/filesystem", name="filesystem")
	 * @Method("POST")
	 * @Template()
	 */
	public function indexAction(Application $app)
	{
		$finder = new Finder();
		$finder->files()
			->in($app['root_dir'] . 'web/contents/')
			->name('/.*\.png|jpe?g/')
			->depth('== 0');
		
		$headers = array(
				'image' => '#'
		);
		
		$files = array();
		
		foreach ($finder as $file) {
			$files[] = $file->getFilename();
		}
		
		return $app['twig']->render('FileSystem\index.html.twig', array(
				'headers' => $headers,
				'files' 	=> $files
		));
	}
	
	/**
	 * @Route("/filesystem/{command}", name="command")
   * @Method("POST")
   * @Template()
   */
	public function commandAction($command, Request $request, Application $app)
	{
		$files = array_map(function ($file) use ($app) {
			return $app['root_dir'] . '/web/' . $file;
		}, $request->get('files'));
		
		try {
			$app->json($app['filesystem']->$command($files));
		} catch (IOException $e) {
			return $app->json(false);
		}
		
		return $app->json($request->get('files'));
	}
}
