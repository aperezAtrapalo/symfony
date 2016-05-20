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

class CollectionValidatorArrayObjectTest extends CollectionValidatorTest
{
    public function prepareTestData(array $contents)
    {
        return new \ArrayObject($contents);
    }
}
