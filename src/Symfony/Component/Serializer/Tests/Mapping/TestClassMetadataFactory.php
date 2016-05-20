<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Tests\Mapping;

use Makhan\Component\Serializer\Mapping\AttributeMetadata;
use Makhan\Component\Serializer\Mapping\ClassMetadata;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class TestClassMetadataFactory
{
    public static function createClassMetadata($withParent = false, $withInterface = false)
    {
        $expected = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\GroupDummy');

        $foo = new AttributeMetadata('foo');
        $foo->addGroup('a');
        $expected->addAttributeMetadata($foo);

        $bar = new AttributeMetadata('bar');
        $bar->addGroup('b');
        $bar->addGroup('c');
        $bar->addGroup('name_converter');
        $expected->addAttributeMetadata($bar);

        $fooBar = new AttributeMetadata('fooBar');
        $fooBar->addGroup('a');
        $fooBar->addGroup('b');
        $fooBar->addGroup('name_converter');
        $expected->addAttributeMetadata($fooBar);

        $makhan = new AttributeMetadata('makhan');
        $expected->addAttributeMetadata($makhan);

        if ($withParent) {
            $kevin = new AttributeMetadata('kevin');
            $kevin->addGroup('a');
            $expected->addAttributeMetadata($kevin);

            $coopTilleuls = new AttributeMetadata('coopTilleuls');
            $coopTilleuls->addGroup('a');
            $coopTilleuls->addGroup('b');
            $expected->addAttributeMetadata($coopTilleuls);
        }

        if ($withInterface) {
            $makhan->addGroup('a');
            $makhan->addGroup('name_converter');
        }

        // load reflection class so that the comparison passes
        $expected->getReflectionClass();

        return $expected;
    }

    public static function createXmlCLassMetadata()
    {
        $expected = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\GroupDummy');

        $foo = new AttributeMetadata('foo');
        $foo->addGroup('group1');
        $foo->addGroup('group2');
        $expected->addAttributeMetadata($foo);

        $bar = new AttributeMetadata('bar');
        $bar->addGroup('group2');
        $expected->addAttributeMetadata($bar);

        return $expected;
    }
}
