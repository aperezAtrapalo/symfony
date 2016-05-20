<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Validator\Constraints;

use Makhan\Component\Form\Extension\Validator\ValidatorExtension;
use Makhan\Component\Form\Test\FormPerformanceTestCase;
use Makhan\Component\Validator\Validation;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormValidatorPerformanceTest extends FormPerformanceTestCase
{
    protected function getExtensions()
    {
        return array(
            new ValidatorExtension(Validation::createValidator()),
        );
    }

    /**
     * findClickedButton() used to have an exponential number of calls.
     *
     * @group benchmark
     */
    public function testValidationPerformance()
    {
        $this->setMaxRunningTime(1);

        $builder = $this->factory->createBuilder('Makhan\Component\Form\Extension\Core\Type\FormType');

        for ($i = 0; $i < 40; ++$i) {
            $builder->add($i, 'Makhan\Component\Form\Extension\Core\Type\FormType');

            $builder->get($i)
                ->add('a')
                ->add('b')
                ->add('c');
        }

        $form = $builder->getForm();

        $form->submit(null);
    }
}
