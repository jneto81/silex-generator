<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Samuca\Fashion\Entity\Network;
use Samuca\Fashion\Form\NetworkType;

/**
 * Network controller.
 *
 * @Route("/network")
 * @Method("GET")
 */
class NetworkController extends Controller
{
    /**
     * Lists all Network entities.
     *
     * @Route("/", name="network")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Application $app)
    {
			$entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Network')
				->findAll();
			
			return $app['twig']->render('Network\index.html.twig', array(
					'entities' => $entities,
			));
    }

    /**
     * Finds and displays a Network entity.
     *
     * @Route("/{id}/show", name="network_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Network')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Network entity.');
			}

						$deleteForm = $this->createDeleteForm($id);
						
			return $app['twig']->render('Network\show.html.twig', array(
				'entity'      => $entity,
								'delete_form' => $deleteForm->createView()			));
    }

    /**
     * Displays a form to create a new Network entity.
     *
     * @Route("/new", name="network_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Application $app)
    {
        $entity = new Network();
				
				$form = $app['form.factory']->create(new NetworkType(), $entity, array());
				
				return $app['twig']->render('Network\new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
    }

    /**
     * Creates a new Network entity.
     *
     * @Route("/create", name="network_create")
     * @Method("POST")
     * @Template("Network:Network:new.html.twig")
     */
    public function createAction(Request $request, Application $app)
    {
			$entity  = new Network();
			$form = $app['form.factory']->create(new NetworkType(), $entity, array());
			$form->bind($request);

			if ($form->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
	
          return $app->redirect($app['url_generator']->generate('network_show', array(
						'id' => $entity->getId()
					)));
      }
			
			return $app['twig']->render('Network\new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView()
			));
    }

    /**
     * Displays a form to edit an existing Network entity.
     *
     * @Route("/{id}/edit", name="network_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Network')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Network entity.');
			}

			$editForm = $app['form.factory']->create(new NetworkType(), $entity, array());
			$deleteForm = $this->createDeleteForm($id);

			return $app['twig']->render('Network\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Edits an existing Network entity.
     *
     * @Route("/{id}/update", name="network_update")
     * @Method("POST")
     * @Template("Network:Network:edit.html.twig")
     */
    public function updateAction(Request $request, $id, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Network')
				->find($id);

			if ( ! $entity) {
				return $app->abort(404, 'Unable to find Network entity.');
			}

			$deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new NetworkType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
					
					return $app->redirect($app['url_generator']->generate('network_show', array(
						'id' => $id
					)));
			}

			return $app['twig']->render('Network\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Deletes a Network entity.
     *
     * @Route("/{id}/delete", name="network_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, Application $app)
    {
			$form = $this->createDeleteForm($id);
			$form->bind($request);

			if ($form->isValid()) {
					$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Network')
						->find($id);

					if ( ! $entity) {
						return $app->abort(404, 'Unable to find Network entity.');
					}

					$app['db.orm.em']->remove($entity);
					$app['db.orm.em']->flush();
			}
			
			return $app->redirect($app['url_generator']->generate('network'));
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
