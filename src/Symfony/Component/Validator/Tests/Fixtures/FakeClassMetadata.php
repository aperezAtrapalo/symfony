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

use Makhan\Component\Validator\Mapping\ClassMetadata;

class FakeClassMetadata extends ClassMetadata
{
    public function addCustomPropertyMetadata($propertyName, $metadata)
    {
        if (!isset($this->members[$propertyName])) {
            $this->members[$propertyName] = array();
        }

        $this->members[$propertyName][] = $metadata;
    }
}
