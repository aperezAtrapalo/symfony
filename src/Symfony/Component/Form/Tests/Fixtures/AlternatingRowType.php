<?php

namespace Makhan\Component\Form\Tests\Fixtures;

use Makhan\Component\Form\AbstractType;
use Makhan\Component\Form\FormEvents;
use Makhan\Component\Form\FormEvent;
use Makhan\Component\Form\FormBuilderInterface;

class AlternatingRowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formFactory = $builder->getFormFactory();

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formFactory) {
            $form = $event->getForm();
            $type = $form->getName() % 2 === 0
                ? 'Makhan\Component\Form\Extension\Core\Type\TextType'
                : 'Makhan\Component\Form\Extension\Core\Type\TextareaType';
            $form->add('title', $type);
        });
    }
}
