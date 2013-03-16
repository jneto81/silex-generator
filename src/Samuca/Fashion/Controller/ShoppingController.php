<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Samuca\Fashion\Entity\Shopping;
use Samuca\Fashion\Form\ShoppingType;

/**
 * Shopping controller.
 *
 * @Route("/shopping")
 * @Method("GET")
 */
class ShoppingController extends Controller
{
    /**
     * Lists all Shopping entities.
     *
     * @Route("/", name="shopping")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Application $app)
    {
			$entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Shopping')
				->findAll();
			
			return $app['twig']->render('Shopping\index.html.twig', array(
					'entities' => $entities,
			));
    }

    /**
     * Finds and displays a Shopping entity.
     *
     * @Route("/{id}/show", name="shopping_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Shopping')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Shopping entity.');
			}

						$deleteForm = $this->createDeleteForm($id);
						
			return $app['twig']->render('Shopping\show.html.twig', array(
				'entity'      => $entity,
								'delete_form' => $deleteForm->createView()			));
    }

    /**
     * Displays a form to create a new Shopping entity.
     *
     * @Route("/new", name="shopping_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Application $app)
    {
        $entity = new Shopping();
				
				$form = $app['form.factory']->create(new ShoppingType(), $entity, array());
				
				return $app['twig']->render('Shopping\new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
    }

    /**
     * Creates a new Shopping entity.
     *
     * @Route("/create", name="shopping_create")
     * @Method("POST")
     * @Template("Shopping:Shopping:new.html.twig")
     */
    public function createAction(Request $request, Application $app)
    {
			$entity  = new Shopping();
			$form = $app['form.factory']->create(new ShoppingType(), $entity, array());
			$form->bind($request);

			if ($form->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
	
										return $app->redirect($app['url_generator']->generate('shopping_show', array(
						'id' => $entity->getId()
					)));
								}
			
			return $app['twig']->render('Shopping\new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView()
			));
    }

    /**
     * Displays a form to edit an existing Shopping entity.
     *
     * @Route("/{id}/edit", name="shopping_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Shopping')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Shopping entity.');
			}

			$editForm = $app['form.factory']->create(new ShoppingType(), $entity, array());
			$deleteForm = $this->createDeleteForm($id);

			return $app['twig']->render('Shopping\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Edits an existing Shopping entity.
     *
     * @Route("/{id}/update", name="shopping_update")
     * @Method("POST")
     * @Template("Shopping:Shopping:edit.html.twig")
     */
    public function updateAction(Request $request, $id, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Shopping')
				->find($id);

			if ( ! $entity) {
				return $app->abort(404, 'Unable to find Shopping entity.');
			}

			$deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new ShoppingType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
					
					return $app->redirect($app['url_generator']->generate('shopping_edit', array(
						'id' => $id
					)));
			}

			return $app['twig']->render('Shopping\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Deletes a Shopping entity.
     *
     * @Route("/{id}/delete", name="shopping_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, Application $app)
    {
			$form = $this->createDeleteForm($id);
			$form->bind($request);

			if ($form->isValid()) {
					$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Shopping')
						->find($id);

					if ( ! $entity) {
						return $app->abort(404, 'Unable to find Shopping entity.');
					}

					$app['db.orm.em']->remove($entity);
					$app['db.orm.em']->flush();
			}
			
			return $app->redirect($app['url_generator']->generate('shopping'));
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
