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
      
      //return $app['twig']->render('default.html.twig', array());
    }
    
    /**
     * @Route("/list/{page}", name="list", defaults={"page" = 1})
     * @Template()
     */
    public function listAction($page = 1, Request $request, Application $app)
    {
      $data = new Brand();
      $type = new SearchType();
      $search = array();
      $values = $request->get($type->getName(), array());
      $limit = $app['const.pagination'];
      
      if ( ! empty($values)) {
        array_walk($values, function ($value, $key) use (&$search, $data) {
          if (strpos($key, '_')  !== 0 && ! empty($value)) {              
              $search[$key] = $value;
              $setter = 'set' . ucfirst($key);
              $data->$setter($value);
          }
        });
      }
      
      $list = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
        ->findBy($search);
        
      $paginator = new Paginator($list, $limit);
      
      $form = $app['form.factory']->create($type, $data, array())
        ->createView();

      return $app['twig']->render('list.html.twig', array(
        'list'            => $paginator->get($page),
        'pages'           => $paginator->pages(),
        'show_pagination' => $paginator->count() > 1,
        'search_form'     => $form,
        'current_page'    => $page,
        'is_dashed'       => true
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
        ->findById($id);
        
      $form = $app['form.factory']->create(new SearchType(), new Brand(), array())
        ->createView();
    
      return $app['twig']->render('show.html.twig', array(
        'entity'        => $entity,
        'search_form'   => $form,
        'is_dashed'     => false,
        'referer'       => $request->headers->get('referer')
      ));
    }
    
    /**
     * @Route("/upload", name="upload")
     * @Method("POST")
     * @Template()
     */
    public function uploadAction(Request $request, Application $app)
    {
      
    }
}
