<?php

namespace KTU\CountersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CounterType extends AbstractType
{
    private $submitText;
    private $validationGroups;
    private $translationDomain;

    public function __construct($submitText, $validationGroups, $translationDomain)
    {
        $this->submitText = $submitText;
        $this->validationGroups = $validationGroups;
        $this->translationDomain = $translationDomain;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cat', null, array('label' => 'counter.create.form.category'))
            ->add('name', 'text', array('label' => 'counter.create.form.name'))
            ->add('url', 'text', array('label' => 'counter.create.form.url'))
            ->add('counterDesc', 'textarea', array('label' => 'counter.create.form.description'))
            ->add('create', 'submit', array('label' => $this->submitText));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => $this->validationGroups,
            'data_class' => 'KTU\CountersBundle\Entity\Counters',
            'translation_domain' => $this->translationDomain
        ));
    }

    public function getName()
    {
        return 'counter';
    }
}