<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CompoundFormPerformanceTest extends \Makhan\Component\Form\Test\FormPerformanceTestCase
{
    /**
     * Create a compound form multiple times, as happens in a collection form.
     *
     * @group benchmark
     */
    public function testArrayBasedForm()
    {
        $this->setMaxRunningTime(1);

        for ($i = 0; $i < 40; ++$i) {
            $form = $this->factory->createBuilder('Makhan\Component\Form\Extension\Core\Type\FormType')
                ->add('firstName', 'Makhan\Component\Form\Extension\Core\Type\TextType')
                ->add('lastName', 'Makhan\Component\Form\Extension\Core\Type\TextType')
                ->add('gender', 'Makhan\Component\Form\Extension\Core\Type\ChoiceType', array(
                    'choices' => array('male' => 'Male', 'female' => 'Female'),
                    'required' => false,
                ))
                ->add('age', 'Makhan\Component\Form\Extension\Core\Type\NumberType')
                ->add('birthDate', 'Makhan\Component\Form\Extension\Core\Type\BirthdayType')
                ->add('city', 'Makhan\Component\Form\Extension\Core\Type\ChoiceType', array(
                    // simulate 300 different cities
                    'choices' => range(1, 300),
                ))
                ->getForm();

            // load the form into a view
            $form->createView();
        }
    }
}
