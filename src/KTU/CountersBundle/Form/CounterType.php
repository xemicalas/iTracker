<?php

namespace KTU\CountersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CounterType extends AbstractType
{
    private $submitText;
    private $validationGroups;

    public function __construct($submitText, $validationGroups)
    {
        $this->submitText = $submitText;
        $this->validationGroups = $validationGroups;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cat', null, array('label' => 'Category'))
            ->add('name', 'text', array('label' => 'Counter name'))
            ->add('url', 'text', array('label' => 'Counter URL'))
            ->add('counterDesc', 'textarea', array('label' => 'Counter description'))
            ->add('create', 'submit', array('label' => $this->submitText));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => $this->validationGroups,
            'data_class' => 'KTU\CountersBundle\Entity\Counters'
        ));
    }

    public function getName()
    {
        return 'counter';
    }
}