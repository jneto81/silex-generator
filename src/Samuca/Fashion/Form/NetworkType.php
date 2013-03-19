<?php

namespace Samuca\Fashion\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'choice', array(
              'choices' => array(
                'googleplus'  => 'Google+',
                'facebook'    => 'Facebook',
                'instagram'   => 'Instagram',
                'pinterest'   => 'Pinterest',
                'twitter'     => 'Twitter',
            )))
            ->add('link')
            ->add('brand', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Brand',
              'property' => 'name'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Samuca\Fashion\Entity\Network'
        ));
    }

    public function getName()
    {
        return 'samuca_fashion_networktype';
    }
}
