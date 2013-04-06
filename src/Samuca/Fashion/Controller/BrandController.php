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
use Kitpages\DataGridBundle\Model\GridConfig;
use Kitpages\DataGridBundle\Model\PaginatorConfig;
use Kitpages\DataGridBundle\Model\Field;

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
    public function indexAction(Request $request, Application $app)
    {
      $entities = $app['db.orm.em']->getRepository('Samuca\Fashion\Entity\Brand')
				->findAll();

      return $app['twig']->render('Brand\index.html.twig', array(
        'entities' => $entities,
			));
    }
     
    public function gridAction(Request $request, Application $app)
    {
      $queryBuilder = $app['db.orm.em']->createQueryBuilder()
        ->select('brand, segment.name AS s_name, region.name AS r_name')
        ->from('Samuca\Fashion\Entity\Brand', 'brand')
        ->leftJoin('brand.segment', 'segment')
        ->leftJoin('brand.region', 'region')
        ;
      
      $paginatorConfig = new PaginatorConfig();
      $paginatorConfig->setCountFieldName("brand.id");
      $paginatorConfig->setItemCountInPage(10);
      $paginatorConfig->setQueryBuilder($queryBuilder);
      $queryBuilder = $paginatorConfig->getQueryBuilder();
      
      //echo $queryBuilder->getQuery()->getSQL();
      
      $gridConfig = new GridConfig();
      $gridConfig->setCountFieldName('brand.id')
        ->addField(new Field('brand.id', array(
          'label' => '#',
        )))
        ->addField(new Field('brand.name', array(
          'filterable' => true,
          'sortable' => true,
          'label' => 'Name'
        )))
        ->addField(new Field('brand.logo', array(
          'label' => 'Logo',
          'autoEscape' => false,
          'formatValueCallback' => function ($value) { 
            return empty($value) ? '' : '<img src="/uploads/thumbs/' . $value . '">'; 
          }
        )))
        ->addField(new Field('s_name', array(
          'label' => 'Segment'
        )))
        ->addField(new Field('brand.description', array(
          'label' => 'Description',
          'formatValueCallback' => function ($value) { 
            return html_entity_decode(\Samir\Twig\Extensions\TextExtension::wordwrap($value, 50, "...")); 
          }
        )))
        ->addField(new Field('brand.type', array(
          'label' => 'Type'
        )))
        ->addField(new Field('brand.keyword', array(
          'label' => 'Keyword'
        )))
        ->addField(new Field('r_name', array(
          'label' => 'Region'
        )))
        ->setPaginatorConfig($paginatorConfig)
      ;

      $grid = $app['kitpages.gm']->getGrid($queryBuilder, $gridConfig, $request);
      $paginator = $app['kitpages.gm']->getPaginator($queryBuilder, $paginatorConfig, $request);
      
			return $app['twig']->render('Brand\grid.html.twig', array(
        'grid'  =>  $grid,
        'paginator' => $paginator
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
        'delete_form' => $deleteForm->createView()			
      ));
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
      
      $originalAddresses = array();
      $originalNetworks = array();
      
      foreach ($entity->getAddresses() as $address) {
        $originalAddresses[] = $address;
      }
      
      foreach ($entity->getNetworks() as $network) {
        $originalNetworks[] = $network;
      }
      
			$deleteForm = $this->createDeleteForm($id);
			$editForm = $app['form.factory']->create(new BrandType(), $entity, array());
			$editForm->bind($request);

			if ($editForm->isValid()) {
        // filter $original to contain tags no longer present
        foreach ($entity->getAddresses() as $address) {
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

        // remove the relationship between the tag and the Address
        foreach ($originalAddresses as $address) {
            // remove the Brand from the Address
            $entity->getAddresses()->removeElement($address);
            // if it were a ManyToOne relationship, remove the relationship like this
            // $tag->setTask(null);
            $app['db.orm.em']->persist($address);
            // if you wanted to delete the Tag entirely, you can also do that
            //$app['db.orm.em']->remove($address);
        }
        
        // remove the relationship between the tag and the Network
        foreach ($originalNetworks as $network) {
            // remove the Brand from the Network
            $entity->getNetworks()->removeElement($network);
            // if it were a ManyToOne relationship, remove the relationship like this
            // $tag->setTask(null);
            $app['db.orm.em']->persist($network);
            // if you wanted to delete the Tag entirely, you can also do that
            // $app['db.orm.em']->remove($network);
        }      
      
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
