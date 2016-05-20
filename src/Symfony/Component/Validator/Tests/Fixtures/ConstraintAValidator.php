<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Fixtures;

use Makhan\Component\Validator\Constraint;
use Makhan\Component\Validator\ConstraintValidator;
use Makhan\Component\Validator\Context\ExecutionContextInterface;

class ConstraintAValidator extends ConstraintValidator
{
    public static $passedContext;

    public function initialize(ExecutionContextInterface $context)
    {
        parent::initialize($context);

        self::$passedContext = $context;
    }

    public function validate($value, Constraint $constraint)
    {
        if ('VALID' != $value) {
            $this->context->addViolation('message', array('param' => 'value'));

            return;
        }
    }
}
