<?php

namespace KTU\CountersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CounterColorType extends AbstractType
{
    private $validationGroups;

    public function __construct($validationGroups)
    {
        $this->validationGroups = $validationGroups;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('backgroundColor', 'text',
            array('label' => 'Background color',
            'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('borderColor', 'text',
                array('label' => 'Border color',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('textColor', 'text',
                array('label' => 'Text color',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('uniqueColor', 'text',
                array('label' => 'Unique number\'s color',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('totalColor', 'text',
                array('label' => 'Total number\'s color',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('barTotalColor', 'text',
                array('label' => 'Strip\'s total background color',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('barUniqueColor', 'text',
                array('label' => 'Strip\'s unique background color',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('transparentBackground', 'checkbox',
                array('label' => 'Use transparent background', 'required' => false))
            ->add('edit', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => $this->validationGroups,
        ));
    }

    public function getName()
    {
        return 'counterColor';
    }
}