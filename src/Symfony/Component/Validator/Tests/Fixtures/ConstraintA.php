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

/** @Annotation */
class ConstraintA extends Constraint
{
    public $property1;
    public $property2;

    public function getDefaultOption()
    {
        return 'property2';
    }

    public function getTargets()
    {
        return array(self::PROPERTY_CONSTRAINT, self::CLASS_CONSTRAINT);
    }
}
