<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Samuca\Fashion\Entity\Address;
use Samuca\Fashion\Form\AddressType;

/**
 * Address controller.
 *
 * @Route("/address")
 * @Method("GET")
 */
class AddressController extends Controller
{
    /**
     * Lists all Address entities.
     *
     * @Route("/", name="address")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Application $app)
    {
			$entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Address')
				->findAll();
			
			return $app['twig']->render('Address\index.html.twig', array(
					'entities' => $entities,
			));
    }

    /**
     * Finds and displays a Address entity.
     *
     * @Route("/{id}/show", name="address_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Address')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Address entity.');
			}

						$deleteForm = $this->createDeleteForm($id);
						
			return $app['twig']->render('Address\show.html.twig', array(
				'entity'      => $entity,
								'delete_form' => $deleteForm->createView()			));
    }

    /**
     * Displays a form to create a new Address entity.
     *
     * @Route("/new", name="address_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Application $app)
    {
        $entity = new Address();
				
				$form = $app['form.factory']->create(new AddressType(), $entity, array());
				
				return $app['twig']->render('Address\new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
    }

    /**
     * Creates a new Address entity.
     *
     * @Route("/create", name="address_create")
     * @Method("POST")
     * @Template("Address:Address:new.html.twig")
     */
    public function createAction(Request $request, Application $app)
    {
			$entity  = new Address();
			$form = $app['form.factory']->create(new AddressType(), $entity, array());
			$form->bind($request);

			if ($form->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
	
          return $app->redirect($app['url_generator']->generate('address_show', array(
						'id' => $entity->getId()
					)));
      }
			
			return $app['twig']->render('Address\new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView()
			));
    }

    /**
     * Displays a form to edit an existing Address entity.
     *
     * @Route("/{id}/edit", name="address_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Address')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Address entity.');
			}

			$editForm = $app['form.factory']->create(new AddressType(), $entity, array());
			$deleteForm = $this->createDeleteForm($id);

			return $app['twig']->render('Address\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Edits an existing Address entity.
     *
     * @Route("/{id}/update", name="address_update")
     * @Method("POST")
     * @Template("Address:Address:edit.html.twig")
     */
    public function updateAction(Request $request, $id, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Address')
				->find($id);

			if ( ! $entity) {
				return $app->abort(404, 'Unable to find Address entity.');
			}

			$deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new AddressType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
					
					return $app->redirect($app['url_generator']->generate('address_show', array(
						'id' => $id
					)));
			}

			return $app['twig']->render('Address\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Deletes a Address entity.
     *
     * @Route("/{id}/delete", name="address_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, Application $app)
    {
			$form = $this->createDeleteForm($id);
			$form->bind($request);

			if ($form->isValid()) {
					$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Address')
						->find($id);

					if ( ! $entity) {
						return $app->abort(404, 'Unable to find Address entity.');
					}

					$app['db.orm.em']->remove($entity);
					$app['db.orm.em']->flush();
			}
			
			return $app->redirect($app['url_generator']->generate('address'));
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
