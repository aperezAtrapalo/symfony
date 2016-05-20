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

use Makhan\Component\PropertyAccess\Tests\Fixtures\NonTraversableArrayObject;

class PropertyAccessorNonTraversableArrayObjectTest extends PropertyAccessorArrayAccessTest
{
    protected function getContainer(array $array)
    {
        return new NonTraversableArrayObject($array);
    }
}
