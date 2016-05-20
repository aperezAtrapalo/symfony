<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\PropertyAccess\Tests;

class PropertyAccessorArrayTest extends PropertyAccessorCollectionTest
{
    protected function getContainer(array $array)
    {
        return $array;
    }
}
