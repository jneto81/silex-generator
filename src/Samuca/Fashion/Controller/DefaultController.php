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
      $data = new Brand();
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
              $search[$key] = $value;
              $setter = 'set' . ucfirst($key);
              $data->$setter($value);
          }
        });
      }
      
      if (isset($search['keyword'])) {
        $search['keyword'] = $search['name'] = implode(' OR ', array_map(function ($value) {
          return "%$value%";
        }, explode($search['keyword'])));
      }
      
      if (isset($letter)) {
        $search['name'] = "$letter%";
      }
      
      $list = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
        ->findByWildcard($search);
        
      $paginator = new Paginator($list, $limit);
      
      $form = $app['form.factory']->create($type, $data, array())
        ->createView();        
        
      $featured = $app['db.orm.em']->createQuery("SELECT b FROM Samuca\Fashion\Entity\Brand b ORDER BY b.id DESC")
        ->setMaxResults(3)
        ->getResult();
        
      return $app['twig']->render('list.html.twig', array(
        'list'            => $paginator->get($page),
        'pages'           => $paginator->pages(),
        'show_pagination' => $paginator->count() > 1,
        'page_count'      => $paginator->count(),
        'search_form'     => $form,
        'current_page'    => $page,
        'base_page'       => $base_page,
        'current_type'    => isset($values['type']) ? $values['type'] : 1,
        'current_index'   => $letter,
        'featured'        => $featured
      ));
    }
    
    /**
     * @Route("/show/{id}", name="show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Request $request, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
        ->find($id);
        
      $form = $app['form.factory']->create(new SearchType(), new Brand(), array())
        ->createView();
    
      return $app['twig']->render('show.html.twig', array(
        'entity'        => $entity,
        'search_form'   => $form,
        'referer_url'       => $request->headers->get('referer')
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
}
