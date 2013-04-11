<?php

namespace Samuca\Fashion\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('segment', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Segment',
              'property' => 'name'
            ))
            ->add('description', 'textarea', array(
              'required' => false,
            	'attr' => array(
            			'class' => 'tiny_mce'
            	)))
						->add('type', 'choice', array(
              'choices' => array(
                'retail'    => 'retail',
                'wholesale' => 'wholesale'
              )
            ))
            ->add('keyword')
            ->add('region', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Region',
              'property' => 'name'
            ))
            ->add('addresses', 'collection', array(
              'type'         => new AddressType(),
              'allow_add'    => true,
              'by_reference' => false,
              'allow_delete' => true
            ))
            ->add('networks', 'collection', array(
              'type'         => new NetworkType(),
              'allow_add'    => true,
              'by_reference' => false,
              'allow_delete' => true
            ))
            ->add('logo')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Samuca\Fashion\Entity\Brand'
        ));
    }

    public function getName()
    {
        return 'samuca_fashion_brandtype';
    }
}
