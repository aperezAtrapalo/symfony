<?php

namespace Makhan\Component\Form\Tests\Fixtures;

use Makhan\Component\Form\AbstractType;
use Makhan\Component\Form\FormBuilderInterface;
use Makhan\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Makhan\Component\Form\Tests\Fixtures\Author',
        ));
    }
}
