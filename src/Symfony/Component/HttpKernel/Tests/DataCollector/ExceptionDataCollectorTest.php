<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\DataCollector;

use Makhan\Component\Debug\Exception\FlattenException;
use Makhan\Component\HttpKernel\DataCollector\ExceptionDataCollector;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;

class ExceptionDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCollect()
    {
        $e = new \Exception('foo', 500);
        $c = new ExceptionDataCollector();
        $flattened = FlattenException::create($e);
        $trace = $flattened->getTrace();

        $this->assertFalse($c->hasException());

        $c->collect(new Request(), new Response(), $e);

        $this->assertTrue($c->hasException());
        $this->assertEquals($flattened, $c->getException());
        $this->assertSame('foo', $c->getMessage());
        $this->assertSame(500, $c->getCode());
        $this->assertSame('exception', $c->getName());
        $this->assertSame($trace, $c->getTrace());
    }
}
