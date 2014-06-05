<?php

namespace KTU\CountersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CounterColorType extends AbstractType
{
    private $validationGroups;
    private $translationDomain;

    public function __construct($validationGroups, $translationDomain)
    {
        $this->validationGroups = $validationGroups;
        $this->translationDomain = $translationDomain;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('backgroundColor', 'text',
            array('label' => 'counter.editColors.form.backgroundColor',
            'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('borderColor', 'text',
                array('label' => 'counter.editColors.form.borderColor',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('textColor', 'text',
                array('label' => 'counter.editColors.form.textColor',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('uniqueColor', 'text',
                array('label' => 'counter.editColors.form.uniqueColor',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('totalColor', 'text',
                array('label' => 'counter.editColors.form.totalColor',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('barTotalColor', 'text',
                array('label' => 'counter.editColors.form.barTotalColor',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('barUniqueColor', 'text',
                array('label' => 'counter.editColors.form.barUniqueColor',
                'label_attr' => array('class' => 'col-sm-3 control-label')))
            ->add('transparentBackground', 'checkbox',
                array('label' => 'counter.editColors.form.transparentBackground', 'required' => false))
            ->add('edit', 'submit', array('label' => 'counter.editColors.form.submit'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => $this->validationGroups,
            'translation_domain' => $this->translationDomain
        ));
    }

    public function getName()
    {
        return 'counterColor';
    }
}