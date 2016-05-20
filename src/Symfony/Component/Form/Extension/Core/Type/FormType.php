<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Core\Type;

use Makhan\Component\Form\FormBuilderInterface;
use Makhan\Component\Form\FormInterface;
use Makhan\Component\Form\FormView;
use Makhan\Component\Form\Extension\Core\EventListener\TrimListener;
use Makhan\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Makhan\Component\Form\Exception\LogicException;
use Makhan\Component\OptionsResolver\Options;
use Makhan\Component\OptionsResolver\OptionsResolver;
use Makhan\Component\PropertyAccess\PropertyAccess;
use Makhan\Component\PropertyAccess\PropertyAccessorInterface;

class FormType extends BaseType
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $isDataOptionSet = array_key_exists('data', $options);

        $builder
            ->setRequired($options['required'])
            ->setErrorBubbling($options['error_bubbling'])
            ->setEmptyData($options['empty_data'])
            ->setPropertyPath($options['property_path'])
            ->setMapped($options['mapped'])
            ->setByReference($options['by_reference'])
            ->setInheritData($options['inherit_data'])
            ->setCompound($options['compound'])
            ->setData($isDataOptionSet ? $options['data'] : null)
            ->setDataLocked($isDataOptionSet)
            ->setDataMapper($options['compound'] ? new PropertyPathMapper($this->propertyAccessor) : null)
            ->setMethod($options['method'])
            ->setAction($options['action']);

        if ($options['trim']) {
            $builder->addEventSubscriber(new TrimListener());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $name = $form->getName();

        if ($view->parent) {
            if ('' === $name) {
                throw new LogicException('Form node with empty name can be used only as root form node.');
            }

            // Complex fields are read-only if they themselves or their parents are.
            if (!isset($view->vars['attr']['readonly']) && isset($view->parent->vars['attr']['readonly']) && false !== $view->parent->vars['attr']['readonly']) {
                $view->vars['attr']['readonly'] = true;
            }
        }

        $view->vars = array_replace($view->vars, array(
            'errors' => $form->getErrors(),
            'valid' => $form->isSubmitted() ? $form->isValid() : true,
            'value' => $form->getViewData(),
            'data' => $form->getNormData(),
            'required' => $form->isRequired(),
            'size' => null,
            'label_attr' => $options['label_attr'],
            'compound' => $form->getConfig()->getCompound(),
            'method' => $form->getConfig()->getMethod(),
            'action' => $form->getConfig()->getAction(),
            'submitted' => $form->isSubmitted(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $multipart = false;

        foreach ($view->children as $child) {
            if ($child->vars['multipart']) {
                $multipart = true;
                break;
            }
        }

        $view->vars['multipart'] = $multipart;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        // Derive "data_class" option from passed "data" object
        $dataClass = function (Options $options) {
            return isset($options['data']) && is_object($options['data']) ? get_class($options['data']) : null;
        };

        // Derive "empty_data" closure from "data_class" option
        $emptyData = function (Options $options) {
            $class = $options['data_class'];

            if (null !== $class) {
                return function (FormInterface $form) use ($class) {
                    return $form->isEmpty() && !$form->isRequired() ? null : new $class();
                };
            }

            return function (FormInterface $form) {
                return $form->getConfig()->getCompound() ? array() : '';
            };
        };

        // For any form that is not represented by a single HTML control,
        // errors should bubble up by default
        $errorBubbling = function (Options $options) {
            return $options['compound'];
        };

        // If data is given, the form is locked to that data
        // (independent of its value)
        $resolver->setDefined(array(
            'data',
        ));

        $resolver->setDefaults(array(
            'data_class' => $dataClass,
            'empty_data' => $emptyData,
            'trim' => true,
            'required' => true,
            'property_path' => null,
            'mapped' => true,
            'by_reference' => true,
            'error_bubbling' => $errorBubbling,
            'label_attr' => array(),
            'inherit_data' => false,
            'compound' => true,
            'method' => 'POST',
            // According to RFC 2396 (http://www.ietf.org/rfc/rfc2396.txt)
            // section 4.2., empty URIs are considered same-document references
            'action' => '',
            'attr' => array(),
            'post_max_size_message' => 'The uploaded file was too large. Please try to upload a smaller file.',
        ));

        $resolver->setAllowedTypes('label_attr', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'form';
    }
}
