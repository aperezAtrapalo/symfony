<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Validator\EventListener;

use Makhan\Component\EventDispatcher\EventSubscriberInterface;
use Makhan\Component\Form\Extension\Validator\ViolationMapper\ViolationMapperInterface;
use Makhan\Component\Validator\Validator\ValidatorInterface;
use Makhan\Component\Form\FormEvents;
use Makhan\Component\Form\FormEvent;
use Makhan\Component\Form\Extension\Validator\Constraints\Form;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ValidationListener implements EventSubscriberInterface
{
    private $validator;

    private $violationMapper;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::POST_SUBMIT => 'validateForm');
    }

    public function __construct(ValidatorInterface $validator, ViolationMapperInterface $violationMapper)
    {
        $this->validator = $validator;
        $this->violationMapper = $violationMapper;
    }

    /**
     * Validates the form and its domain object.
     *
     * @param FormEvent $event The event object
     */
    public function validateForm(FormEvent $event)
    {
        $form = $event->getForm();

        if ($form->isRoot()) {
            // Validate the form in group "Default"
            $violations = $this->validator->validate($form);

            foreach ($violations as $violation) {
                // Allow the "invalid" constraint to be put onto
                // non-synchronized forms
                // ConstraintViolation::getConstraint() must not expect to provide a constraint as long as Makhan\Component\Validator\ExecutionContext exists (before 3.0)
                $allowNonSynchronized = (null === $violation->getConstraint() || $violation->getConstraint() instanceof Form) && Form::NOT_SYNCHRONIZED_ERROR === $violation->getCode();

                $this->violationMapper->mapViolation($violation, $form, $allowNonSynchronized);
            }
        }
    }
}
