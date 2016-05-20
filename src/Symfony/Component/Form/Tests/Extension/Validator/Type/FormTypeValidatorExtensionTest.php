<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Validator\Type;

use Makhan\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Makhan\Component\Validator\Constraints\Valid;
use Makhan\Component\Validator\ConstraintViolationList;

class FormTypeValidatorExtensionTest extends BaseValidatorExtensionTest
{
    public function testSubmitValidatesData()
    {
        $builder = $this->factory->createBuilder(
            'Makhan\Component\Form\Extension\Core\Type\FormType',
            null,
            array(
                'validation_groups' => 'group',
            )
        );
        $builder->add('firstName', 'Makhan\Component\Form\Extension\Core\Type\FormType');
        $form = $builder->getForm();

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($form))
            ->will($this->returnValue(new ConstraintViolationList()));

        // specific data is irrelevant
        $form->submit(array());
    }

    public function testValidConstraint()
    {
        $form = $this->createForm(array('constraints' => $valid = new Valid()));

        $this->assertSame(array($valid), $form->getConfig()->getOption('constraints'));
    }

    public function testValidatorInterface()
    {
        $validator = $this->getMock('Makhan\Component\Validator\Validator\ValidatorInterface');

        $formTypeValidatorExtension = new FormTypeValidatorExtension($validator);
        $this->assertAttributeSame($validator, 'validator', $formTypeValidatorExtension);
    }

    protected function createForm(array $options = array())
    {
        return $this->factory->create('Makhan\Component\Form\Extension\Core\Type\FormType', null, $options);
    }
}
