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

use Makhan\Component\HttpKernel\DataCollector\TimeDataCollector;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;

/**
 * @group time-sensitive
 */
class TimeDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCollect()
    {
        $c = new TimeDataCollector();

        $request = new Request();
        $request->server->set('REQUEST_TIME', 1);

        $c->collect($request, new Response());

        $this->assertEquals(1000, $c->getStartTime());

        $request->server->set('REQUEST_TIME_FLOAT', 2);

        $c->collect($request, new Response());

        $this->assertEquals(2000, $c->getStartTime());

        $request = new Request();
        $c->collect($request, new Response());
        $this->assertEquals(0, $c->getStartTime());

        $kernel = $this->getMock('Makhan\Component\HttpKernel\KernelInterface');
        $kernel->expects($this->once())->method('getStartTime')->will($this->returnValue(123456));

        $c = new TimeDataCollector($kernel);
        $request = new Request();
        $request->server->set('REQUEST_TIME', 1);

        $c->collect($request, new Response());
        $this->assertEquals(123456000, $c->getStartTime());
    }
}
