<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator;

use Makhan\Component\Validator\Constraints\ExpressionValidator;

/**
 * Default implementation of the ConstraintValidatorFactoryInterface.
 *
 * This enforces the convention that the validatedBy() method on any
 * Constraint will return the class name of the ConstraintValidator that
 * should validate the Constraint.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ConstraintValidatorFactory implements ConstraintValidatorFactoryInterface
{
    protected $validators = array();

    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getInstance(Constraint $constraint)
    {
        $className = $constraint->validatedBy();

        if (!isset($this->validators[$className])) {
            $this->validators[$className] = 'validator.expression' === $className
                ? new ExpressionValidator()
                : new $className();
        }

        return $this->validators[$className];
    }
}
