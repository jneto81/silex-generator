<?php

namespace Samuca\Fashion\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      global $app;
    
        $builder
            ->add('title')
            ->add('caption')
            ->add('brand', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Brand',
              'property' => 'name'
            ))
            ->add('src', 'bootstrap_file', array(
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
            'data_class' => 'Samuca\Fashion\Entity\Media'
        ));
    }

    public function getName()
    {
        return 'samuca_fashion_mediatype';
    }
}
