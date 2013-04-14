<?php

namespace Samuca\Fashion\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Samuca\Fashion\Entity\Poster;
use Samuca\Fashion\Form\PosterType;
use Kitpages\DataGridBundle\Model\GridConfig;
use Kitpages\DataGridBundle\Model\PaginatorConfig;
use Kitpages\DataGridBundle\Model\Field;

/**
 * Poster controller.
 *
 * @Route("/poster")
 * @Method("GET")
 */
class PosterController extends Controller
{
    public function gridAction(Request $request, Application $app)
    {
      $queryBuilder = $app['db.orm.em']->createQueryBuilder()
        ->select('poster')
        ->from('Samuca\Fashion\Entity\Poster', 'poster')
        ->leftJoin('poster.brand', 'brand')
        ->where('brand.id IS NULL')
        ;
      
      $paginatorConfig = new PaginatorConfig();
      $paginatorConfig->setCountFieldName("poster.id");
      $paginatorConfig->setItemCountInPage(10);
      $paginatorConfig->setQueryBuilder($queryBuilder);
      $queryBuilder = $paginatorConfig->getQueryBuilder();
      
      //echo $queryBuilder->getQuery()->getSQL();
      
      $gridConfig = new GridConfig();
      $gridConfig->setCountFieldName('poster.id')
        ->addField(new Field('poster.id', array(
          'label' => '#',
        )))
        ->addField(new Field('poster.size', array(
          'label' => 'Size',
          'translatable' => true
        )))
         ->addField(new Field('poster.link', array(
          'label' => 'Link'
        )))
        ->addField(new Field('poster.src', array(
          'label' => 'Image',
          'autoEscape' => false,          
          'formatValueCallback' => function ($value) { 
            return empty($value) ? '' : '<img src="/uploads/thumbs/' . $value . '">'; 
          }
        )))
        ->setPaginatorConfig($paginatorConfig)
      ;

      $grid = $app['kitpages.gm']->getGrid($queryBuilder, $gridConfig, $request);
      $paginator = $app['kitpages.gm']->getPaginator($queryBuilder, $paginatorConfig, $request);
      $entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
				->findAll();
      
			return $app['twig']->render('Poster\grid.html.twig', array(
        'grid'  =>  $grid,
        'entities' => $entities, 
        'paginator' => $paginator
			));
    }

    /**
     * Finds and displays a Poster entity.
     *
     * @Route("/{id}/show", name="poster_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Poster')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Poster entity.');
			}

			$deleteForm = $this->createDeleteForm($id);
						
			return $app['twig']->render('Poster\show.html.twig', array(
				'entity'      => $entity,
        'delete_form' => $deleteForm->createView()			
      ));
    }

    /**
     * Displays a form to create a new Poster entity.
     *
     * @Route("/new", name="poster_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Application $app)
    {
        $entity = new Poster();
				
				$form = $app['form.factory']->create(new PosterType(), $entity, array());
				
				return $app['twig']->render('Poster\new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
    }

    /**
     * Creates a new Poster entity.
     *
     * @Route("/create", name="poster_create")
     * @Method("POST")
     * @Template("Poster:Poster:new.html.twig")
     */
    public function createAction(Request $request, Application $app)
    {
			$entity  = new Poster();
			$form = $app['form.factory']->create(new PosterType(), $entity, array());
			$form->bind($request);

			if ($form->isValid()) {
        $app['db.orm.em']->persist($entity);
        $app['db.orm.em']->flush();

        return $app->redirect($app['url_generator']->generate('poster_show', array(
          'id' => $entity->getId()
        )));
      }
			
			return $app['twig']->render('Poster\new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView()
			));
    }

    /**
     * Displays a form to edit an existing Poster entity.
     *
     * @Route("/{id}/edit", name="poster_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Application $app)
    {
			$entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Poster')
				->find($id);

			if ( ! $entity) {
					return $app->abort(404, 'Unable to find Poster entity.');
			}

			$editForm = $app['form.factory']->create(new PosterType(), $entity, array());
			$deleteForm = $this->createDeleteForm($id);

			return $app['twig']->render('Poster\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Edits an existing Poster entity.
     *
     * @Route("/{id}/update", name="poster_update")
     * @Method("POST")
     * @Template("Poster:Poster:edit.html.twig")
     */
    public function updateAction(Request $request, $id, Application $app)
    {
      $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Poster')
				->find($id);

			if ( ! $entity) {
				return $app->abort(404, 'Unable to find Poster entity.');
			}

			$deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new PosterType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
        $app['db.orm.em']->persist($entity);
        $app['db.orm.em']->flush();
        
        return $app->redirect($app['url_generator']->generate('poster_show', array(
          'id' => $id
        )));
			}

			return $app['twig']->render('Poster\edit.html.twig', array(
					'entity'      => $entity,
					'edit_form'   => $editForm->createView(),
					'delete_form' => $deleteForm->createView(),
			));
    }

    /**
     * Deletes a Poster entity.
     *
     * @Route("/{id}/delete", name="poster_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, Application $app)
    {
			$form = $this->createDeleteForm($id);
			$form->bind($request);

			if ($form->isValid()) {
        $entity = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Poster')
          ->find($id);

        if ( ! $entity) {
          return $app->abort(404, 'Unable to find Poster entity.');
        }

        $app['db.orm.em']->remove($entity);
        $app['db.orm.em']->flush();
			}
			
			return $app->redirect($app['url_generator']->generate('poster'));
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
