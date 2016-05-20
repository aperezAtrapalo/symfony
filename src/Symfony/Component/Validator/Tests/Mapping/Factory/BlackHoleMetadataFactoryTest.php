<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Mapping\Factory;

use Makhan\Component\Validator\Mapping\Factory\BlackHoleMetadataFactory;

class BlackHoleMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testGetMetadataForThrowsALogicException()
    {
        $metadataFactory = new BlackHoleMetadataFactory();
        $metadataFactory->getMetadataFor('foo');
    }

    public function testHasMetadataForReturnsFalse()
    {
        $metadataFactory = new BlackHoleMetadataFactory();

        $this->assertFalse($metadataFactory->hasMetadataFor('foo'));
    }
}
