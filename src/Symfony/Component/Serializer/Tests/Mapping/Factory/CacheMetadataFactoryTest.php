<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Tests\Mapping\Factory;

use Makhan\Component\Cache\Adapter\ArrayAdapter;
use Makhan\Component\Serializer\Mapping\ClassMetadata;
use Makhan\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Makhan\Component\Serializer\Mapping\Factory\CacheClassMetadataFactory;
use Makhan\Component\Serializer\Tests\Fixtures\Dummy;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class CacheMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMetadataFor()
    {
        $metadata = new ClassMetadata(Dummy::class);

        $decorated = $this->getMock(ClassMetadataFactoryInterface::class);
        $decorated
            ->expects($this->once())
            ->method('getMetadataFor')
            ->will($this->returnValue($metadata))
        ;

        $factory = new CacheClassMetadataFactory($decorated, new ArrayAdapter());

        $this->assertEquals($metadata, $factory->getMetadataFor(Dummy::class));
        // The second call should retrieve the value from the cache
        $this->assertEquals($metadata, $factory->getMetadataFor(Dummy::class));
    }

    public function testHasMetadataFor()
    {
        $decorated = $this->getMock(ClassMetadataFactoryInterface::class);
        $decorated
            ->expects($this->once())
            ->method('hasMetadataFor')
            ->will($this->returnValue(true))
        ;

        $factory = new CacheClassMetadataFactory($decorated, new ArrayAdapter());

        $this->assertTrue($factory->hasMetadataFor(Dummy::class));
    }

    /**
     * @expectedException \Makhan\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testInvalidClassThrowsException()
    {
        $decorated = $this->getMock(ClassMetadataFactoryInterface::class);
        $factory = new CacheClassMetadataFactory($decorated, new ArrayAdapter());

        $factory->getMetadataFor('Not\Exist');
    }
}
