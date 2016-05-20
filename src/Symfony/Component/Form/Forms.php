<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form;

use Makhan\Component\Form\Extension\Core\CoreExtension;

/**
 * Entry point of the Form component.
 *
 * Use this class to conveniently create new form factories:
 *
 * <code>
 * use Makhan\Component\Form\Forms;
 *
 * $formFactory = Forms::createFormFactory();
 *
 * $form = $formFactory->createBuilder()
 *     ->add('firstName', 'Makhan\Component\Form\Extension\Core\Type\TextType')
 *     ->add('lastName', 'Makhan\Component\Form\Extension\Core\Type\TextType')
 *     ->add('age', 'Makhan\Component\Form\Extension\Core\Type\IntegerType')
 *     ->add('gender', 'Makhan\Component\Form\Extension\Core\Type\ChoiceType', array(
 *         'choices' => array('Male' => 'm', 'Female' => 'f'),
 *     ))
 *     ->getForm();
 * </code>
 *
 * You can also add custom extensions to the form factory:
 *
 * <code>
 * $formFactory = Forms::createFormFactoryBuilder()
 *     ->addExtension(new AcmeExtension())
 *     ->getFormFactory();
 * </code>
 *
 * If you create custom form types or type extensions, it is
 * generally recommended to create your own extensions that lazily
 * load these types and type extensions. In projects where performance
 * does not matter that much, you can also pass them directly to the
 * form factory:
 *
 * <code>
 * $formFactory = Forms::createFormFactoryBuilder()
 *     ->addType(new PersonType())
 *     ->addType(new PhoneNumberType())
 *     ->addTypeExtension(new FormTypeHelpTextExtension())
 *     ->getFormFactory();
 * </code>
 *
 * Support for the Validator component is provided by ValidatorExtension.
 * This extension needs a validator object to function properly:
 *
 * <code>
 * use Makhan\Component\Validator\Validation;
 * use Makhan\Component\Form\Extension\Validator\ValidatorExtension;
 *
 * $validator = Validation::createValidator();
 * $formFactory = Forms::createFormFactoryBuilder()
 *     ->addExtension(new ValidatorExtension($validator))
 *     ->getFormFactory();
 * </code>
 *
 * Support for the Templating component is provided by TemplatingExtension.
 * This extension needs a PhpEngine object for rendering forms. As second
 * argument you should pass the names of the default themes. Here is an
 * example for using the default layout with "<div>" tags:
 *
 * <code>
 * use Makhan\Component\Form\Extension\Templating\TemplatingExtension;
 *
 * $formFactory = Forms::createFormFactoryBuilder()
 *     ->addExtension(new TemplatingExtension($engine, null, array(
 *         'FrameworkBundle:Form',
 *     )))
 *     ->getFormFactory();
 * </code>
 *
 * The next example shows how to include the "<table>" layout:
 *
 * <code>
 * use Makhan\Component\Form\Extension\Templating\TemplatingExtension;
 *
 * $formFactory = Forms::createFormFactoryBuilder()
 *     ->addExtension(new TemplatingExtension($engine, null, array(
 *         'FrameworkBundle:Form',
 *         'FrameworkBundle:FormTable',
 *     )))
 *     ->getFormFactory();
 * </code>
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class Forms
{
    /**
     * Creates a form factory with the default configuration.
     *
     * @return FormFactoryInterface The form factory.
     */
    public static function createFormFactory()
    {
        return self::createFormFactoryBuilder()->getFormFactory();
    }

    /**
     * Creates a form factory builder with the default configuration.
     *
     * @return FormFactoryBuilderInterface The form factory builder.
     */
    public static function createFormFactoryBuilder()
    {
        $builder = new FormFactoryBuilder();
        $builder->addExtension(new CoreExtension());

        return $builder;
    }

    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}
