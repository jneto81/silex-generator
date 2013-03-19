<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Samuca\Fashion\Entity\Media;
use Samuca\Fashion\Form\MediaType;

/**
 * Media controller.
 *
 * @Route("/media")
 * @Method("GET")
 */
class MediaController extends Controller
{
    /**
     * Lists all Media entities.
     *
     * @Route("/", name="media")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Application $app)
    {
			$entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Media')
				->findAll();
			
			return $app['twig']->render('Media\index.html.twig', array(
					'entities' => $entities,
			));
    }

    /**
     * Finds and displays a Media entity.
     *
     * @Route("/{id}/show", name="media_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Media')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Media entity.');
			}

			$deleteForm = $this->createDeleteForm($id);
						
			return $app['twig']->render('Media\show.html.twig', array(
				'entity'      => $entity,
        'delete_form' => $deleteForm->createView()			
      ));
    }

    /**
     * Displays a form to create a new Media entity.
     *
     * @Route("/new", name="media_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Application $app)
    {
        $entity = new Media();
				
				$form = $app['form.factory']->create(new MediaType(), $entity, array());
				
				return $app['twig']->render('Media\new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
    }

    /**
     * Creates a new Media entity.
     *
     * @Route("/create", name="media_create")
     * @Method("POST")
     * @Template("Media:Media:new.html.twig")
     */
    public function createAction(Request $request, Application $app)
    {
			$entity  = new Media();
			$form = $app['form.factory']->create(new MediaType(), $entity, array());
			$form->bind($request);

			if ($form->isValid()) {
        $app['db.orm.em']->persist($entity);
        $app['db.orm.em']->flush();

        return $app->redirect($app['url_generator']->generate('media_show', array(
          'id' => $entity->getId()
        )));
      }
			
			return $app['twig']->render('Media\new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView()
			));
    }

    /**
     * Displays a form to edit an existing Media entity.
     *
     * @Route("/{id}/edit", name="media_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Media')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Media entity.');
			}

			$editForm = $app['form.factory']->create(new MediaType(), $entity, array());
			$deleteForm = $this->createDeleteForm($id);

			return $app['twig']->render('Media\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Edits an existing Media entity.
     *
     * @Route("/{id}/update", name="media_update")
     * @Method("POST")
     * @Template("Media:Media:edit.html.twig")
     */
    public function updateAction(Request $request, $id, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Media')
				->find($id);

			if ( ! $entity) {
				return $app->abort(404, 'Unable to find Media entity.');
			}

			$deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new MediaType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
        $app['db.orm.em']->persist($entity);
        $app['db.orm.em']->flush();
        
        return $app->redirect($app['url_generator']->generate('media_edit', array(
          'id' => $id
        )));
			}

			return $app['twig']->render('Media\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Deletes a Media entity.
     *
     * @Route("/{id}/delete", name="media_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, Application $app)
    {
			$form = $this->createDeleteForm($id);
			$form->bind($request);

			if ($form->isValid()) {
        $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Media')
          ->find($id);

        if ( ! $entity) {
          return $app->abort(404, 'Unable to find Media entity.');
        }

        $app['db.orm.em']->remove($entity);
        $app['db.orm.em']->flush();
			}
			
			return $app->redirect($app['url_generator']->generate('media'));
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