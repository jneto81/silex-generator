<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/list", name="list")
     * @Method("GET")
     * @Template()
     */
    public function listAction(Request $request, Application $app)
    {
      return $app['twig']->render('index.html.twig', array(
          
      ));
    }
    
    /**
     * @Route("/show", name="show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, Application $app)
    {
      return $app['twig']->render('show.html.twig', array(
          
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
