<?php

namespace Samuca\Fashion\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('segment', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Brand',
              'property' => 'segment',
              'required' => false,
              'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('b')
                  ->groupBy('b.segment')
                  ;              
              },
            ))
            ->add('region', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Brand',
              'property' => 'region',
              'required' => false,
              'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('b')
                  ->groupBy('b.region')              
                  ;
              },
            ))
            ->add('type', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Brand',
              'property' => 'type',
              'required' => false,
              'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('b')
                  ->groupBy('b.type')
                  ;
              },
            ))
            ->add('keyword', 'text', array(
              'required' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'samuca_fashion_searchtype';
    }
}
