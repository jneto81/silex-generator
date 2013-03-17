<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Samuca\Fashion\Entity\Brand;
use Samuca\Fashion\Form\BrandType;

/**
 * Brand controller.
 *
 * @Route("/brand")
 * @Method("GET")
 */
class BrandController extends Controller
{
    /**
     * Lists all Brand entities.
     *
     * @Route("/", name="brand")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Application $app)
    {
			$entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
				->findAll();
			
			return $app['twig']->render('Brand\index.html.twig', array(
					'entities' => $entities,
			));
    }

    /**
     * Finds and displays a Brand entity.
     *
     * @Route("/{id}/show", name="brand_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Brand entity.');
			}

						$deleteForm = $this->createDeleteForm($id);
						
			return $app['twig']->render('Brand\show.html.twig', array(
				'entity'      => $entity,
								'delete_form' => $deleteForm->createView()			));
    }

    /**
     * Displays a form to create a new Brand entity.
     *
     * @Route("/new", name="brand_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Application $app)
    {
        $entity = new Brand();
				
				$form = $app['form.factory']->create(new BrandType(), $entity, array());
				
				return $app['twig']->render('Brand\new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
    }

    /**
     * Creates a new Brand entity.
     *
     * @Route("/create", name="brand_create")
     * @Method("POST")
     * @Template("Brand\new.html.twig")
     */
    public function createAction(Request $request, Application $app)
    {
			$entity  = new Brand();
			$form = $app['form.factory']->create(new BrandType(), $entity, array());
			$form->bind($request);

			if ($form->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
	
										return $app->redirect($app['url_generator']->generate('brand_show', array(
						'id' => $entity->getId()
					)));
								}
			
			return $app['twig']->render('Brand\new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView()
			));
    }

    /**
     * Displays a form to edit an existing Brand entity.
     *
     * @Route("/{id}/edit", name="brand_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Brand entity.');
			}

			$editForm = $app['form.factory']->create(new BrandType(), $entity, array());
			$deleteForm = $this->createDeleteForm($id);

			return $app['twig']->render('Brand\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Edits an existing Brand entity.
     *
     * @Route("/{id}/update", name="brand_update")
     * @Method("POST")
     * @Template("Brand\edit.html.twig")
     */
    public function updateAction(Request $request, $id, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
				->find($id);

			if ( ! $entity) {
				return $app->abort(404, 'Unable to find Brand entity.');
			}

			$deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new BrandType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
					
					return $app->redirect($app['url_generator']->generate('brand_edit', array(
						'id' => $id
					)));
			}

			return $app['twig']->render('Brand\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Deletes a Brand entity.
     *
     * @Route("/{id}/delete", name="brand_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, Application $app)
    {
			$form = $this->createDeleteForm($id);
			$form->bind($request);

			if ($form->isValid()) {
					$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
						->find($id);

					if ( ! $entity) {
						return $app->abort(404, 'Unable to find Brand entity.');
					}

					$app['db.orm.em']->remove($entity);
					$app['db.orm.em']->flush();
			}
			
			return $app->redirect($app['url_generator']->generate('brand'));
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
