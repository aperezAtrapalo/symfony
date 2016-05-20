<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Constraints;

use Makhan\Component\Validator\Constraint;
use Makhan\Component\Validator\ConstraintValidator;
use Makhan\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IsFalseValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsFalse) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\IsFalse');
        }

        if (null === $value || false === $value || 0 === $value || '0' === $value) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->setCode(IsFalse::NOT_FALSE_ERROR)
            ->addViolation();
    }
}
