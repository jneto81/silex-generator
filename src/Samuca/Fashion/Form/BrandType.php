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
            ->add('logo')
            ->add('segment')
            ->add('description', 'textarea', array(
            	'attr' => array(
            			'class' => 'tiny_mce'
            	)))
						->add('type')
            ->add('keyword')
            ->add('region')
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
