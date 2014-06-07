<?php

namespace KTU\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountersType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_id', null, array('label' => 'User'))
            ->add('cat', null, array('label' => 'Category'))
            ->add('name', null, array('label' => 'Name'))
            ->add('url', null, array('label' => 'URL'))
            ->add('counterdesc', null, array('label' => 'Description'))
            ->add('backgroundColor', null, array('label' => 'Background Color'))
            ->add('borderColor', null, array('label' => 'Border color'))
            ->add('textColor', null, array('label' => 'Text color'))
            ->add('uniqueColor', null, array('label' => 'Unique number\'s color'))
            ->add('totalColor', null, array('label' => 'Total number\'s color'))
            ->add('barTotalColor', null, array('label' => 'Strip\'s total background color'))
            ->add('barUniqueColor', null, array('label' => 'Strip\'s unique background color'))
            ->add('transparentBackground')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KTU\CountersBundle\Entity\Counters'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ktu_adminbundle_counters';
    }
}
