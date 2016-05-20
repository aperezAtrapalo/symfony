<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Mapping;

use Makhan\Component\Validator\Mapping\PropertyMetadata;
use Makhan\Component\Validator\Tests\Fixtures\Entity;

class PropertyMetadataTest extends \PHPUnit_Framework_TestCase
{
    const CLASSNAME = 'Makhan\Component\Validator\Tests\Fixtures\Entity';
    const PARENTCLASS = 'Makhan\Component\Validator\Tests\Fixtures\EntityParent';

    public function testInvalidPropertyName()
    {
        $this->setExpectedException('Makhan\Component\Validator\Exception\ValidatorException');

        new PropertyMetadata(self::CLASSNAME, 'foobar');
    }

    public function testGetPropertyValueFromPrivateProperty()
    {
        $entity = new Entity('foobar');
        $metadata = new PropertyMetadata(self::CLASSNAME, 'internal');

        $this->assertEquals('foobar', $metadata->getPropertyValue($entity));
    }

    public function testGetPropertyValueFromOverriddenPrivateProperty()
    {
        $entity = new Entity('foobar');
        $metadata = new PropertyMetadata(self::PARENTCLASS, 'data');

        $this->assertTrue($metadata->isPublic($entity));
        $this->assertEquals('Overridden data', $metadata->getPropertyValue($entity));
    }
}
