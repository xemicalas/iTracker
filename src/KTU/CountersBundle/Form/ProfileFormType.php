<?php

namespace KTU\CountersBundle\Form;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseFormType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends BaseFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('username');
    }

    public function getName()
    {
        return 'ktu_counters_profile_edit';
    }
}