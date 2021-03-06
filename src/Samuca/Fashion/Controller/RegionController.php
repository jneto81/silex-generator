<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Samuca\Fashion\Entity\Region;
use Samuca\Fashion\Form\RegionType;

/**
 * Region controller.
 *
 * @Route("/region")
 * @Method("GET")
 */
class RegionController extends Controller
{
    /**
     * Lists all Region entities.
     *
     * @Route("/", name="region")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Application $app)
    {
			$entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Region')
				->findAll();
			
			return $app['twig']->render('Region\index.html.twig', array(
					'entities' => $entities,
			));
    }

    /**
     * Finds and displays a Region entity.
     *
     * @Route("/{id}/show", name="region_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Region')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Region entity.');
			}

						$deleteForm = $this->createDeleteForm($id);
						
			return $app['twig']->render('Region\show.html.twig', array(
				'entity'      => $entity,
        'delete_form' => $deleteForm->createView()			
      ));
    }

    /**
     * Displays a form to create a new Region entity.
     *
     * @Route("/new", name="region_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Application $app)
    {
        $entity = new Region();
				
				$form = $app['form.factory']->create(new RegionType(), $entity, array());
				
				return $app['twig']->render('Region\new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
    }

    /**
     * Creates a new Region entity.
     *
     * @Route("/create", name="region_create")
     * @Method("POST")
     * @Template("Region\new.html.twig")
     */
    public function createAction(Request $request, Application $app)
    {
			$entity  = new Region();
			$form = $app['form.factory']->create(new RegionType(), $entity, array());
			$form->bind($request);
      
			if ($form->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
	
          return $app->redirect($app['url_generator']->generate('region_show', array(
						'id' => $entity->getId()
					)));
      }
			
			return $app['twig']->render('Region\new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView()
			));
    }

    /**
     * Displays a form to edit an existing Region entity.
     *
     * @Route("/{id}/edit", name="region_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Region')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Region entity.');
			}
      
      $editForm = $app['form.factory']->create(new RegionType(), $entity, array());
			$deleteForm = $this->createDeleteForm($id);

			return $app['twig']->render('Region\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Edits an existing Region entity.
     *
     * @Route("/{id}/update", name="region_update")
     * @Method("POST")
     * @Template("Region\edit.html.twig")
     */
    public function updateAction(Request $request, $id, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Region')
				->find($id);

			if ( ! $entity) {
				return $app->abort(404, 'Unable to find Region entity.');
			}
      
      $deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new RegionType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
        	$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
					
					return $app->redirect($app['url_generator']->generate('region_show', array(
						'id' => $id
					)));
			}

			return $app['twig']->render('Region\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Deletes a Region entity.
     *
     * @Route("/{id}/delete", name="region_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, Application $app)
    {
			$form = $this->createDeleteForm($id);
			$form->bind($request);

			if ($form->isValid()) {
					$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Region')
						->find($id);

					if ( ! $entity) {
						return $app->abort(404, 'Unable to find Region entity.');
					}
          
          $addresses = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Address')
						->findByRegion($id);
            
          if ($addresses) {
            foreach ($addresses as $address) {
              $address->setRegion(null);
              $app['db.orm.em']->persist($address);
            }
          }
          
					$app['db.orm.em']->remove($entity);
					$app['db.orm.em']->flush();
			}
			
			return $app->redirect($app['url_generator']->generate('region'));
    }

    private function createDeleteForm($id)
    {
			global $app;
			
			return $app['form.factory']->createBuilder('form', array('id' => $id), array())
					->add('id', 'hidden')
					->getForm()
			;
    }
}
