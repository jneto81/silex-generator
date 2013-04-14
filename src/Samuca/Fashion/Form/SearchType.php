<?php

namespace Samuca\Fashion\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Samuca\Fashion\Entity\Brand;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('segment', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Segment',
              'property' => 'name',
              'required' => false
            ))
            ->add('region', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Region',
              'property' => 'name',
              'required' => false
            ))
            ->add('type', 'choice', array(
              'choices' => array(
                Brand::TYPE_RETAIL => Brand::TYPE_RETAIL, 
                Brand::TYPE_WHOLESALE => Brand::TYPE_WHOLESALE,
              ),
              'required' => false,
            ))
            ->add('keyword', 'text', array(
              'attr' => array(
                'class' => 'input-small'
              ),
              'required' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'samuca_fashion_searchtype';
    }
}
