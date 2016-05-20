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

use Makhan\Component\ExpressionLanguage\ExpressionLanguage;
use Makhan\Component\Validator\Constraint;
use Makhan\Component\Validator\ConstraintValidator;
use Makhan\Component\Validator\Exception\RuntimeException;
use Makhan\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Bernhard Schussek <bschussek@makhan.com>
 */
class ExpressionValidator extends ConstraintValidator
{
    /**
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    public function __construct($propertyAccessor = null, ExpressionLanguage $expressionLanguage = null)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Expression) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Expression');
        }

        $variables = array();
        $variables['value'] = $value;
        $variables['this'] = $this->context->getObject();

        if (!$this->getExpressionLanguage()->evaluate($constraint->expression, $variables)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Expression::EXPRESSION_FAILED_ERROR)
                ->addViolation();
        }
    }

    private function getExpressionLanguage()
    {
        if (null === $this->expressionLanguage) {
            if (!class_exists('Makhan\Component\ExpressionLanguage\ExpressionLanguage')) {
                throw new RuntimeException('Unable to use expressions as the Makhan ExpressionLanguage component is not installed.');
            }
            $this->expressionLanguage = new ExpressionLanguage();
        }

        return $this->expressionLanguage;
    }
}
