<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Samuca\Fashion\Entity\Segment;
use Samuca\Fashion\Form\SegmentType;

/**
 * Segment controller.
 *
 * @Route("/segment")
 * @Method("GET")
 */
class SegmentController extends Controller
{
    /**
     * Lists all Segment entities.
     *
     * @Route("/", name="segment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Application $app)
    {
			$entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Segment')
				->findAll();
			
			return $app['twig']->render('Segment\index.html.twig', array(
					'entities' => $entities,
			));
    }

    /**
     * Finds and displays a Segment entity.
     *
     * @Route("/{id}/show", name="segment_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Segment')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Segment entity.');
			}

						$deleteForm = $this->createDeleteForm($id);
						
			return $app['twig']->render('Segment\show.html.twig', array(
				'entity'      => $entity,
        'delete_form' => $deleteForm->createView()			
      ));
    }

    /**
     * Displays a form to create a new Segment entity.
     *
     * @Route("/new", name="segment_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Application $app)
    {
        $entity = new Segment();
				
				$form = $app['form.factory']->create(new SegmentType(), $entity, array());
				
				return $app['twig']->render('Segment\new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
    }

    /**
     * Creates a new Segment entity.
     *
     * @Route("/create", name="segment_create")
     * @Method("POST")
     * @Template("Segment\new.html.twig")
     */
    public function createAction(Request $request, Application $app)
    {
			$entity  = new Segment();
			$form = $app['form.factory']->create(new SegmentType(), $entity, array());
			$form->bind($request);
      
			if ($form->isValid()) {
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
	
										return $app->redirect($app['url_generator']->generate('segment_show', array(
						'id' => $entity->getId()
					)));
      }
			
			return $app['twig']->render('Segment\new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView()
			));
    }

    /**
     * Displays a form to edit an existing Segment entity.
     *
     * @Route("/{id}/edit", name="segment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Segment')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Segment entity.');
			}
      
      $originalAddresses = array();
      $originalNetworks = array();
      
      foreach ($entity->getAddresses() as $address) {
        $originalAddresses[] = $address;
      }
      
      foreach ($entity->getNetworks() as $networks) {
        $originalNetworks[] = $networks;
      }

			$editForm = $app['form.factory']->create(new SegmentType(), $entity, array());
			$deleteForm = $this->createDeleteForm($id);

			return $app['twig']->render('Segment\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Edits an existing Segment entity.
     *
     * @Route("/{id}/update", name="segment_update")
     * @Method("POST")
     * @Template("Segment\edit.html.twig")
     */
    public function updateAction(Request $request, $id, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Segment')
				->find($id);

			if ( ! $entity) {
				return $app->abort(404, 'Unable to find Segment entity.');
			}
      
      $originalAddresses = array();
      $originalNetworks = array();
      
      foreach ($entity->getAddresses() as $address) {
        $originalAddresses[] = $address;
      }
      
      foreach ($entity->getNetworks() as $network) {
        $originalNetworks[] = $network;
      }
      
			$deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new SegmentType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
        // filter $originalTags to contain tags no longer present
        foreach ($task->getAddresses() as $address) {
            foreach ($originalAddresses as $key => $toDel) {
                if ($toDel->getId() === $address->getId()) {
                    unset($originalAddresses[$key]);
                }
            }
        }
        
        foreach ($entity->getNetworks() as $network) {
           foreach ($originalNetworks as $key => $toDel) {
                if ($toDel->getId() === $network->getId()) {
                    unset($originalNetworks[$key]);
                }
            }
        }

        // remove the relationship between the tag and the Task
        foreach ($originalAddresses as $address) {
            // remove the Task from the Tag
            $entity->getAddresses()->removeElement($address);
            // if it were a ManyToOne relationship, remove the relationship like this
            // $tag->setTask(null);
            $app['db.orm.em']->persist($address);
            // if you wanted to delete the Tag entirely, you can also do that
            $app['db.orm.em']->remove($address);
        }
        
        // remove the relationship between the tag and the Task
        foreach ($originalNetworks as $network) {
            // remove the Task from the Tag
            $entity->getNetworks()->removeElement($network);
            // if it were a ManyToOne relationship, remove the relationship like this
            // $tag->setTask(null);
            $app['db.orm.em']->persist($network);
            // if you wanted to delete the Tag entirely, you can also do that
            $app['db.orm.em']->remove($network);
        }
      
      
					$app['db.orm.em']->persist($entity);
					$app['db.orm.em']->flush();
					
					return $app->redirect($app['url_generator']->generate('segment_edit', array(
						'id' => $id
					)));
			}

			return $app['twig']->render('Segment\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Deletes a Segment entity.
     *
     * @Route("/{id}/delete", name="segment_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, Application $app)
    {
			$form = $this->createDeleteForm($id);
			$form->bind($request);

			if ($form->isValid()) {
					$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Segment')
						->find($id);

					if ( ! $entity) {
						return $app->abort(404, 'Unable to find Segment entity.');
					}

					$app['db.orm.em']->remove($entity);
					$app['db.orm.em']->flush();
			}
			
			return $app->redirect($app['url_generator']->generate('segment'));
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
