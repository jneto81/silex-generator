<?php

namespace Samuca\Fashion\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Samuca\Fashion\Entity\Poster;
use Silex\Application;

class PosterType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    global $app;
  
    $builder
      ->add('size', 'choice', array(
        'choices' => array(
          Poster::SIZE_LARGE => Poster::SIZE_LARGE,
          Poster::SIZE_MEDIUM => Poster::SIZE_MEDIUM
        )
      ))
      ->add('link', 'url')
      ->add('brand', 'hidden', array(
        'required' => false,
        'property_path' => false
      ))
      /*
      ->add('brand', 'entity', array(
        'class' => 'Samuca\Fashion\Entity\Brand',
        'property' => 'name',
        'required' => false,
      ))
      */
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
          'data_class' => 'Samuca\Fashion\Entity\Poster'
      ));
  }

  public function getName()
  {
      return 'samuca_fashion_postertype';
  }
}
