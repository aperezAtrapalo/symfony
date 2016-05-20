<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\VarDumper\Tests;

use Makhan\Component\VarDumper\Cloner\VarCloner;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class VarClonerTest extends \PHPUnit_Framework_TestCase
{
    public function testMaxIntBoundary()
    {
        $data = array(PHP_INT_MAX => 123);

        $cloner = new VarCloner();
        $clone = $cloner->cloneVar($data);

        $expected = <<<EOTXT
Makhan\Component\VarDumper\Cloner\Data Object
(
    [data:Makhan\Component\VarDumper\Cloner\Data:private] => Array
        (
            [0] => Array
                (
                    [0] => Makhan\Component\VarDumper\Cloner\Stub Object
                        (
                            [type] => array
                            [class] => assoc
                            [value] => 1
                            [cut] => 0
                            [handle] => 0
                            [refCount] => 0
                            [position] => 1
                        )

                )

            [1] => Array
                (
                    [%s] => 123
                )

        )

    [maxDepth:Makhan\Component\VarDumper\Cloner\Data:private] => 20
    [maxItemsPerDepth:Makhan\Component\VarDumper\Cloner\Data:private] => -1
    [useRefHandles:Makhan\Component\VarDumper\Cloner\Data:private] => -1
)

EOTXT;
        $this->assertSame(sprintf($expected, PHP_INT_MAX), print_r($clone, true));
    }

    public function testClone()
    {
        $json = json_decode('{"1":{"var":"val"},"2":{"var":"val"}}');

        $cloner = new VarCloner();
        $clone = $cloner->cloneVar($json);

        $expected = <<<EOTXT
Makhan\Component\VarDumper\Cloner\Data Object
(
    [data:Makhan\Component\VarDumper\Cloner\Data:private] => Array
        (
            [0] => Array
                (
                    [0] => Makhan\Component\VarDumper\Cloner\Stub Object
                        (
                            [type] => object
                            [class] => stdClass
                            [value] => 
                            [cut] => 0
                            [handle] => %i
                            [refCount] => 0
                            [position] => 1
                        )

                )

            [1] => Array
                (
                    [\000+\0001] => Makhan\Component\VarDumper\Cloner\Stub Object
                        (
                            [type] => object
                            [class] => stdClass
                            [value] => 
                            [cut] => 0
                            [handle] => %i
                            [refCount] => 0
                            [position] => 2
                        )

                    [\000+\0002] => Makhan\Component\VarDumper\Cloner\Stub Object
                        (
                            [type] => object
                            [class] => stdClass
                            [value] => 
                            [cut] => 0
                            [handle] => %i
                            [refCount] => 0
                            [position] => 3
                        )

                )

            [2] => Array
                (
                    [\000+\000var] => val
                )

            [3] => Array
                (
                    [\000+\000var] => val
                )

        )

    [maxDepth:Makhan\Component\VarDumper\Cloner\Data:private] => 20
    [maxItemsPerDepth:Makhan\Component\VarDumper\Cloner\Data:private] => -1
    [useRefHandles:Makhan\Component\VarDumper\Cloner\Data:private] => -1
)

EOTXT;
        $this->assertStringMatchesFormat($expected, print_r($clone, true));
    }

    public function testCaster()
    {
        $cloner = new VarCloner(array(
            '*' => function ($obj, $array) {
                return array('foo' => 123);
            },
            __CLASS__ => function ($obj, $array) {
                ++$array['foo'];

                return $array;
            },
        ));
        $clone = $cloner->cloneVar($this);

        $expected = <<<EOTXT
Makhan\Component\VarDumper\Cloner\Data Object
(
    [data:Makhan\Component\VarDumper\Cloner\Data:private] => Array
        (
            [0] => Array
                (
                    [0] => Makhan\Component\VarDumper\Cloner\Stub Object
                        (
                            [type] => object
                            [class] => %s
                            [value] => 
                            [cut] => 0
                            [handle] => %i
                            [refCount] => 0
                            [position] => 1
                        )

                )

            [1] => Array
                (
                    [foo] => 124
                )

        )

    [maxDepth:Makhan\Component\VarDumper\Cloner\Data:private] => 20
    [maxItemsPerDepth:Makhan\Component\VarDumper\Cloner\Data:private] => -1
    [useRefHandles:Makhan\Component\VarDumper\Cloner\Data:private] => -1
)

EOTXT;
        $this->assertStringMatchesFormat($expected, print_r($clone, true));
    }
}
