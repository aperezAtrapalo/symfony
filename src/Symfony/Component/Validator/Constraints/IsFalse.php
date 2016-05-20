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

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IsFalse extends Constraint
{
    const NOT_FALSE_ERROR = 'd53a91b0-def3-426a-83d7-269da7ab4200';

    protected static $errorNames = array(
        self::NOT_FALSE_ERROR => 'NOT_FALSE_ERROR',
    );

    public $message = 'This value should be false.';
}
