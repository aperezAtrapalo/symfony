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

use Makhan\Component\Validator\Context\ExecutionContextInterface;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CallbackClass
{
    public static function callback($object, ExecutionContextInterface $context)
    {
    }
}
