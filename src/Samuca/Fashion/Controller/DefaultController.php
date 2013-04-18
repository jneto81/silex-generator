<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Samuca\Fashion\Form\SearchType;
use Samuca\Fashion\Entity\Brand;
use Samuca\Fashion\Entity\Poster;
use Samir\Pagination\Paginator;
use Samir\File\Streamer;
use Samir\Image\Thumbnail;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, Application $app)
    {
      return $app['twig']->render('login.html.twig', array(
          'error' => $app['security.last_error']($request),
          'last_username' => $app['session']->get('_security.last_username')
      ));
    }
    
    /**
     * @Route("/list/{param1}/{param2}", name="list", defaults={ "param1" = NULL, "param2" = NULL })
     * @Template()
     */
    public function listAction($param1 = null, $param2 = null, Request $request, Application $app)
    {
      $data = array();
      $type = new SearchType();
      $search = array();
      $values = $request->get($type->getName(), array());
      $limit = $app['const.pagination'];
      $base_page = '';
      $letter = null;      
      $page = 1;
      
      if ( ! empty($param2)) {
        $letter = $param1;
        $page = $param2;
      }
      
      if ( ! empty($param1)) {
        if (is_numeric($param1)) {
          $page = $param1;
        } else {
          $base_page = "$letter/";
          $letter = $param1;
        }
      }
      
      if ( ! empty($values)) {
        array_walk($values, function ($value, $key) use (&$search, $data) {
          if (strpos($key, '_')  !== 0 && ! empty($value)) {              
              $search['b.' . $key] = $value;
              $data[$key] = $value;
          }
        });
      }
      
      if (isset($search['b.keyword'])) {
        $search['b.keyword'] = implode(' OR ', array_map(function ($value) {
          return "%$value%";
        }, explode(' ', $search['b.keyword'])));
      }
      
      if (isset($letter)) {
        $search['b.name'] = "$letter%";
      }
      
      if (isset($search['b.region'])) { 
        $search['a.region'] = $search['b.region'];
        unset($search['b.region']);
      }
      
      $list = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
        ->findByWildcardJoin($search);
        
      $paginator = new Paginator($list, $limit);
      
      $form = $app['form.factory']->create($type, $data, array())
        ->createView();
        
      $featured = $app['db.orm.em']->createQuery("SELECT b FROM Samuca\Fashion\Entity\Brand b ORDER BY b.id DESC")
        ->setMaxResults(3)
        ->getResult();
      
      $posters = $this->getPosters();
      
      return $app['twig']->render('Site\\list.html.twig', array(
        'list'            => $paginator->get($page),
        'pages'           => $paginator->pages(),
        'show_pagination' => $paginator->count() > 1,
        'page_count'      => $paginator->count(),
        'search_form'     => $form,
        'current_page'    => $page,
        'base_page'       => $base_page,
        'current_type'    => isset($values['type']) ? $values['type'] : Brand::TYPE_RETAIL,
        'current_index'   => $letter,
        'types'           => array( Brand::TYPE_RETAIL, Brand::TYPE_WHOLESALE ),
        'featured'        => $featured,
        'poster_large'    => $posters[Poster::SIZE_LARGE],
        'poster_medium'   => $posters[Poster::SIZE_MEDIUM]
      ));
    }
    
    /**
     * @Route("/show/{id}/{name}", name="show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, $name, Request $request, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
        ->find($id);
        
      $form = $app['form.factory']->create(new SearchType(), array(), array())
        ->createView();
        
      $posters = $this->getPosters($entity);
    
      return $app['twig']->render('Site\\show.html.twig', array(
        'entity'        => $entity,
        'search_form'   => $form,
        'referer_url'   => $request->headers->get('referer'),
        'poster_large'    => $posters[Poster::SIZE_LARGE],
        'poster_medium'   => $posters[Poster::SIZE_MEDIUM]
      ));
    }
    
    /**
     * @Route("/upload", name="upload")
     * @Method("POST")
     * @Template()
     */
    public function uploadAction(Request $request, Application $app)
    {
			$output = false;
      
			$dir = $app['root_dir'] . '/web' . $request->get('dir') . '/';
      $thumb_dir = $app['root_dir'] . '/web' . $request->get('dir') . '/thumbs/';              
			
			if ( ! file_exists($dir)) {
				$app['filesystem']->mkdir($dir);
			}
			
      $name = $request->get('name');
      
			$st = new Streamer();
			$st->setDestination($dir . '/', $name);
				
			if ($st->receive()) {
				if ($request->get('thumbnail') == 'true') {
					
					if ( ! file_exists($thumb_dir)) {
						$app['filesystem']->mkdir($thumb_dir);
					}
						
					$thumbnail = new Thumbnail(120, 120);
					$thumbnail->loadFile($dir . '/' . $name);
					$thumbnail->save($thumb_dir . '/' . $name, $request->get('type'));
				}
				
				return $app->json(array(
					'name' => $name,
					'success' => true
				));
			}
		
			return $app->json(false);
		}
    
    private function getPosters(Brand $entity = null)
    {
      global $app;
    
      $posters = array(
        Poster::SIZE_LARGE  => array(),
        Poster::SIZE_MEDIUM => array(),
      );
      
      $defaultPosters = $app['db.orm.em']->createQuery("SELECT p FROM Samuca\Fashion\Entity\Poster p WHERE p.brand IS NULL")
        ->getResult();
      
      if ($defaultPosters) {  
        shuffle($defaultPosters);
      
        foreach ($defaultPosters as $poster) {
          $posters[$poster->getSize()][] = $poster;
        }
      }
      
      if ($entity) {
        $brandPosters = $entity->getPosters()
          ->toArray();
        
        shuffle($brandPosters);
        
        foreach ($brandPosters as $poster) {
          $posters[$poster->getSize()][] = $poster;
        }      
      }
      
      shuffle($posters[Poster::SIZE_LARGE]);
      shuffle($posters[Poster::SIZE_MEDIUM]);
   
      return $posters;
    }
}
