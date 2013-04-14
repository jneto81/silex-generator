<?php

namespace Samuca\Fashion\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('location')            
            ->add('region', 'entity', array(
              'class' => 'Samuca\Fashion\Entity\Region',
              'property' => 'name'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Samuca\Fashion\Entity\Address'
        ));
    }

    public function getName()
    {
        return 'samuca_fashion_addresstype';
    }
}
