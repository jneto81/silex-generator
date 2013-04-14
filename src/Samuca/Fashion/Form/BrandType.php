<?php

namespace Samuca\Fashion\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Samuca\Fashion\Entity\Brand;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      global $app;
    
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
                Brand::TYPE_RETAIL => Brand::TYPE_RETAIL,
                Brand::TYPE_WHOLESALE => Brand::TYPE_WHOLESALE
              )
            ))
            ->add('keyword')
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
            ->add('posters', 'collection', array(
              'type'         => new PosterType(),
              'allow_add'    => true,
              'by_reference' => false,
              'allow_delete' => true
            ))
            ->add('logo', 'bootstrap_file', array(
              'data_class' => null,
              'attr' => array(
                'url'   => $app['url_generator']->generate('upload'),
                'allow' => 'jpe?g|png',
                'dir'   => '/uploads',
              )
            ))
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
