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

use Makhan\Component\Form\FormBuilder;
use Makhan\Component\Form\Exception\TransformationFailedException;
use Makhan\Component\Form\CallbackTransformer;
use Makhan\Component\Form\FormInterface;
use Makhan\Component\Form\Extension\Validator\Constraints\Form;
use Makhan\Component\Form\Extension\Validator\Constraints\FormValidator;
use Makhan\Component\Form\SubmitButtonBuilder;
use Makhan\Component\Validator\Constraints\NotNull;
use Makhan\Component\Validator\Constraints\NotBlank;
use Makhan\Component\Validator\Constraints\Valid;
use Makhan\Component\Validator\ExecutionContextInterface;
use Makhan\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $serverParams;

    protected function setUp()
    {
        $this->dispatcher = $this->getMock('Makhan\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = $this->getMock('Makhan\Component\Form\FormFactoryInterface');
        $this->serverParams = $this->getMock(
            'Makhan\Component\Form\Extension\Validator\Util\ServerParams',
            array('getNormalizedIniPostMaxSize', 'getContentLength')
        );

        parent::setUp();
    }

    protected function createValidator()
    {
        return new FormValidator($this->serverParams);
    }

    public function testValidate()
    {
        $object = $this->getMock('\stdClass');
        $options = array('validation_groups' => array('group1', 'group2'));
        $form = $this->getBuilder('name', '\stdClass', $options)
            ->setData($object)
            ->getForm();

        $this->expectValidateAt(0, 'data', $object, 'group1');
        $this->expectValidateAt(1, 'data', $object, 'group2');

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testValidateConstraints()
    {
        $object = $this->getMock('\stdClass');
        $constraint1 = new NotNull(array('groups' => array('group1', 'group2')));
        $constraint2 = new NotBlank(array('groups' => 'group2'));

        $options = array(
            'validation_groups' => array('group1', 'group2'),
            'constraints' => array($constraint1, $constraint2),
        );
        $form = $this->getBuilder('name', '\stdClass', $options)
            ->setData($object)
            ->getForm();

        // First default constraints
        $this->expectValidateAt(0, 'data', $object, 'group1');
        $this->expectValidateAt(1, 'data', $object, 'group2');

        // Then custom constraints
        $this->expectValidateValueAt(2, 'data', $object, $constraint1, 'group1');
        $this->expectValidateValueAt(3, 'data', $object, $constraint2, 'group2');

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testValidateChildIfValidConstraint()
    {
        $object = $this->getMock('\stdClass');

        $parent = $this->getBuilder('parent')
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->getForm();
        $options = array(
            'validation_groups' => array('group1', 'group2'),
            'constraints' => array(new Valid()),
        );
        $form = $this->getBuilder('name', '\stdClass', $options)->getForm();
        $parent->add($form);

        $form->setData($object);

        $this->expectValidateAt(0, 'data', $object, array('group1', 'group2'));

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testDontValidateIfParentWithoutValidConstraint()
    {
        $object = $this->getMock('\stdClass');

        $parent = $this->getBuilder('parent', null)
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->getForm();
        $options = array('validation_groups' => array('group1', 'group2'));
        $form = $this->getBuilder('name', '\stdClass', $options)->getForm();
        $parent->add($form);

        $form->setData($object);

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testMissingConstraintIndex()
    {
        $object = new \stdClass();
        $form = new FormBuilder('name', '\stdClass', $this->dispatcher, $this->factory);
        $form = $form->setData($object)->getForm();

        $this->expectValidateAt(0, 'data', $object, 'Default');

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testValidateConstraintsOptionEvenIfNoValidConstraint()
    {
        $object = $this->getMock('\stdClass');
        $constraint1 = new NotNull(array('groups' => array('group1', 'group2')));
        $constraint2 = new NotBlank(array('groups' => 'group2'));

        $parent = $this->getBuilder('parent', null)
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->getForm();
        $options = array(
            'validation_groups' => array('group1', 'group2'),
            'constraints' => array($constraint1, $constraint2),
        );
        $form = $this->getBuilder('name', '\stdClass', $options)
            ->setData($object)
            ->getForm();
        $parent->add($form);

        $this->expectValidateValueAt(0, 'data', $object, $constraint1, 'group1');
        $this->expectValidateValueAt(1, 'data', $object, $constraint2, 'group2');

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testDontValidateIfNoValidationGroups()
    {
        $object = $this->getMock('\stdClass');

        $form = $this->getBuilder('name', '\stdClass', array(
                'validation_groups' => array(),
            ))
            ->setData($object)
            ->getForm();

        $form->setData($object);

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testDontValidateConstraintsIfNoValidationGroups()
    {
        $object = $this->getMock('\stdClass');
        $constraint1 = $this->getMock('Makhan\Component\Validator\Constraint');
        $constraint2 = $this->getMock('Makhan\Component\Validator\Constraint');

        $options = array(
            'validation_groups' => array(),
            'constraints' => array($constraint1, $constraint2),
        );
        $form = $this->getBuilder('name', '\stdClass', $options)
            ->setData($object)
            ->getForm();

        // Launch transformer
        $form->submit(array());

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testDontValidateIfNotSynchronized()
    {
        $object = $this->getMock('\stdClass');

        $form = $this->getBuilder('name', '\stdClass', array(
                'invalid_message' => 'invalid_message_key',
                // Invalid message parameters must be supported, because the
                // invalid message can be a translation key
                // see https://github.com/makhan/makhan/issues/5144
                'invalid_message_parameters' => array('{{ foo }}' => 'bar'),
            ))
            ->setData($object)
            ->addViewTransformer(new CallbackTransformer(
                function ($data) { return $data; },
                function () { throw new TransformationFailedException(); }
            ))
            ->getForm();

        // Launch transformer
        $form->submit('foo');

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->buildViolation('invalid_message_key')
            ->setParameter('{{ value }}', 'foo')
            ->setParameter('{{ foo }}', 'bar')
            ->setInvalidValue('foo')
            ->setCode(Form::NOT_SYNCHRONIZED_ERROR)
            ->setCause($form->getTransformationFailure())
            ->assertRaised();
    }

    public function testAddInvalidErrorEvenIfNoValidationGroups()
    {
        $object = $this->getMock('\stdClass');

        $form = $this->getBuilder('name', '\stdClass', array(
                'invalid_message' => 'invalid_message_key',
                // Invalid message parameters must be supported, because the
                // invalid message can be a translation key
                // see https://github.com/makhan/makhan/issues/5144
                'invalid_message_parameters' => array('{{ foo }}' => 'bar'),
                'validation_groups' => array(),
            ))
            ->setData($object)
            ->addViewTransformer(new CallbackTransformer(
                    function ($data) { return $data; },
                    function () { throw new TransformationFailedException(); }
                ))
            ->getForm();

        // Launch transformer
        $form->submit('foo');

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->buildViolation('invalid_message_key')
            ->setParameter('{{ value }}', 'foo')
            ->setParameter('{{ foo }}', 'bar')
            ->setInvalidValue('foo')
            ->setCode(Form::NOT_SYNCHRONIZED_ERROR)
            ->setCause($form->getTransformationFailure())
            ->assertRaised();
    }

    public function testDontValidateConstraintsIfNotSynchronized()
    {
        $object = $this->getMock('\stdClass');
        $constraint1 = $this->getMock('Makhan\Component\Validator\Constraint');
        $constraint2 = $this->getMock('Makhan\Component\Validator\Constraint');

        $options = array(
            'invalid_message' => 'invalid_message_key',
            'validation_groups' => array('group1', 'group2'),
            'constraints' => array($constraint1, $constraint2),
        );
        $form = $this->getBuilder('name', '\stdClass', $options)
            ->setData($object)
            ->addViewTransformer(new CallbackTransformer(
                function ($data) { return $data; },
                function () { throw new TransformationFailedException(); }
            ))
            ->getForm();

        // Launch transformer
        $form->submit('foo');

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->buildViolation('invalid_message_key')
            ->setParameter('{{ value }}', 'foo')
            ->setInvalidValue('foo')
            ->setCode(Form::NOT_SYNCHRONIZED_ERROR)
            ->setCause($form->getTransformationFailure())
            ->assertRaised();
    }

    // https://github.com/makhan/makhan/issues/4359
    public function testDontMarkInvalidIfAnyChildIsNotSynchronized()
    {
        $object = $this->getMock('\stdClass');

        $failingTransformer = new CallbackTransformer(
            function ($data) { return $data; },
            function () { throw new TransformationFailedException(); }
        );

        $form = $this->getBuilder('name', '\stdClass')
            ->setData($object)
            ->addViewTransformer($failingTransformer)
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->add(
                $this->getBuilder('child')
                    ->addViewTransformer($failingTransformer)
            )
            ->getForm();

        // Launch transformer
        $form->submit(array('child' => 'foo'));

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testHandleCallbackValidationGroups()
    {
        $object = $this->getMock('\stdClass');
        $options = array('validation_groups' => array($this, 'getValidationGroups'));
        $form = $this->getBuilder('name', '\stdClass', $options)
            ->setData($object)
            ->getForm();

        $this->expectValidateAt(0, 'data', $object, 'group1');
        $this->expectValidateAt(1, 'data', $object, 'group2');

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testDontExecuteFunctionNames()
    {
        $object = $this->getMock('\stdClass');
        $options = array('validation_groups' => 'header');
        $form = $this->getBuilder('name', '\stdClass', $options)
            ->setData($object)
            ->getForm();

        $this->expectValidateAt(0, 'data', $object, 'header');

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testHandleClosureValidationGroups()
    {
        $object = $this->getMock('\stdClass');
        $options = array('validation_groups' => function (FormInterface $form) {
            return array('group1', 'group2');
        });
        $form = $this->getBuilder('name', '\stdClass', $options)
            ->setData($object)
            ->getForm();

        $this->expectValidateAt(0, 'data', $object, 'group1');
        $this->expectValidateAt(1, 'data', $object, 'group2');

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testUseValidationGroupOfClickedButton()
    {
        $object = $this->getMock('\stdClass');

        $parent = $this->getBuilder('parent')
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->getForm();
        $form = $this->getForm('name', '\stdClass', array(
            'validation_groups' => 'form_group',
            'constraints' => array(new Valid()),
        ));

        $parent->add($form);
        $parent->add($this->getSubmitButton('submit', array(
            'validation_groups' => 'button_group',
        )));

        $parent->submit(array('name' => $object, 'submit' => ''));

        $this->expectValidateAt(0, 'data', $object, array('button_group'));

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testDontUseValidationGroupOfUnclickedButton()
    {
        $object = $this->getMock('\stdClass');

        $parent = $this->getBuilder('parent')
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->getForm();
        $form = $this->getForm('name', '\stdClass', array(
            'validation_groups' => 'form_group',
            'constraints' => array(new Valid()),
        ));

        $parent->add($form);
        $parent->add($this->getSubmitButton('submit', array(
            'validation_groups' => 'button_group',
        )));

        $form->setData($object);

        $this->expectValidateAt(0, 'data', $object, array('form_group'));

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testUseInheritedValidationGroup()
    {
        $object = $this->getMock('\stdClass');

        $parentOptions = array('validation_groups' => 'group');
        $parent = $this->getBuilder('parent', null, $parentOptions)
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->getForm();
        $formOptions = array('constraints' => array(new Valid()));
        $form = $this->getBuilder('name', '\stdClass', $formOptions)->getForm();
        $parent->add($form);

        $form->setData($object);

        $this->expectValidateAt(0, 'data', $object, array('group'));

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testUseInheritedCallbackValidationGroup()
    {
        $object = $this->getMock('\stdClass');

        $parentOptions = array('validation_groups' => array($this, 'getValidationGroups'));
        $parent = $this->getBuilder('parent', null, $parentOptions)
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->getForm();
        $formOptions = array('constraints' => array(new Valid()));
        $form = $this->getBuilder('name', '\stdClass', $formOptions)->getForm();
        $parent->add($form);

        $form->setData($object);

        $this->expectValidateAt(0, 'data', $object, array('group1', 'group2'));

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testUseInheritedClosureValidationGroup()
    {
        $object = $this->getMock('\stdClass');

        $parentOptions = array(
            'validation_groups' => function (FormInterface $form) {
                return array('group1', 'group2');
            },
        );
        $parent = $this->getBuilder('parent', null, $parentOptions)
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->getForm();
        $formOptions = array('constraints' => array(new Valid()));
        $form = $this->getBuilder('name', '\stdClass', $formOptions)->getForm();
        $parent->add($form);

        $form->setData($object);

        $this->expectValidateAt(0, 'data', $object, array('group1', 'group2'));

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testAppendPropertyPath()
    {
        $object = $this->getMock('\stdClass');
        $form = $this->getBuilder('name', '\stdClass')
            ->setData($object)
            ->getForm();

        $this->expectValidateAt(0, 'data', $object, 'Default');

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testDontWalkScalars()
    {
        $form = $this->getBuilder()
            ->setData('scalar')
            ->getForm();

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->assertNoViolation();
    }

    public function testViolationIfExtraData()
    {
        $form = $this->getBuilder('parent', null, array('extra_fields_message' => 'Extra!'))
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->add($this->getBuilder('child'))
            ->getForm();

        $form->submit(array('foo' => 'bar'));

        $this->expectNoValidate();

        $this->validator->validate($form, new Form());

        $this->buildViolation('Extra!')
            ->setParameter('{{ extra_fields }}', 'foo')
            ->setInvalidValue(array('foo' => 'bar'))
            ->setCode(Form::NO_SUCH_FIELD_ERROR)
            ->assertRaised();
    }

    public function testNoViolationIfAllowExtraData()
    {
        $context = $this->getMockExecutionContext();

        $form = $this
            ->getBuilder('parent', null, array('allow_extra_fields' => true))
            ->setCompound(true)
            ->setDataMapper($this->getDataMapper())
            ->add($this->getBuilder('child'))
            ->getForm();

        $form->submit(array('foo' => 'bar'));

        $context->expects($this->never())
            ->method('addViolation');

        $this->validator->initialize($context);
        $this->validator->validate($form, new Form());
    }

    /**
     * Access has to be public, as this method is called via callback array
     * in {@link testValidateFormDataCanHandleCallbackValidationGroups()}
     * and {@link testValidateFormDataUsesInheritedCallbackValidationGroup()}.
     */
    public function getValidationGroups(FormInterface $form)
    {
        return array('group1', 'group2');
    }

    private function getMockExecutionContext()
    {
        $context = $this->getMock('Makhan\Component\Validator\Context\ExecutionContextInterface');
        $validator = $this->getMock('Makhan\Component\Validator\Validator\ValidatorInterface');
        $contextualValidator = $this->getMock('Makhan\Component\Validator\Validator\ContextualValidatorInterface');

        $validator->expects($this->any())
            ->method('inContext')
            ->with($context)
            ->will($this->returnValue($contextualValidator));

        $context->expects($this->any())
            ->method('getValidator')
            ->will($this->returnValue($validator));

        return $context;
    }

    /**
     * @param string $name
     * @param string $dataClass
     * @param array  $options
     *
     * @return FormBuilder
     */
    private function getBuilder($name = 'name', $dataClass = null, array $options = array())
    {
        $options = array_replace(array(
            'constraints' => array(),
            'invalid_message_parameters' => array(),
        ), $options);

        return new FormBuilder($name, $dataClass, $this->dispatcher, $this->factory, $options);
    }

    private function getForm($name = 'name', $dataClass = null, array $options = array())
    {
        return $this->getBuilder($name, $dataClass, $options)->getForm();
    }

    private function getSubmitButton($name = 'name', array $options = array())
    {
        $builder = new SubmitButtonBuilder($name, $options);

        return $builder->getForm();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getDataMapper()
    {
        return $this->getMock('Makhan\Component\Form\DataMapperInterface');
    }
}
