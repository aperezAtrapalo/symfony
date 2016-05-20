<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Constraints;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CountValidatorArrayTest extends CountValidatorTest
{
    protected function createCollection(array $content)
    {
        return $content;
    }
}
