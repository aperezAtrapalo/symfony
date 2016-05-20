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
use Makhan\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Valid extends Constraint
{
    public $traverse = true;

    public function __construct($options = null)
    {
        if (is_array($options) && array_key_exists('groups', $options)) {
            throw new ConstraintDefinitionException(sprintf(
                'The option "groups" is not supported by the constraint %s',
                __CLASS__
            ));
        }

        parent::__construct($options);
    }
}
