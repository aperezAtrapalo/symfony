<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\EventListener;

use Makhan\Component\HttpFoundation\RequestStack;
use Makhan\Component\HttpKernel\EventListener\ProfilerListener;
use Makhan\Component\HttpKernel\Event\FilterResponseEvent;
use Makhan\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Makhan\Component\HttpKernel\Event\PostResponseEvent;
use Makhan\Component\HttpKernel\Exception\HttpException;
use Makhan\Component\HttpKernel\Kernel;

class ProfilerListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test a master and sub request with an exception and `onlyException` profiler option enabled.
     */
    public function testKernelTerminate()
    {
        $profile = $this->getMockBuilder('Makhan\Component\HttpKernel\Profiler\Profile')
            ->disableOriginalConstructor()
            ->getMock();

        $profiler = $this->getMockBuilder('Makhan\Component\HttpKernel\Profiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();

        $profiler->expects($this->once())
            ->method('collect')
            ->will($this->returnValue($profile));

        $kernel = $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface');

        $masterRequest = $this->getMockBuilder('Makhan\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $subRequest = $this->getMockBuilder('Makhan\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $this->getMockBuilder('Makhan\Component\HttpFoundation\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $requestStack = new RequestStack();
        $requestStack->push($masterRequest);

        $onlyException = true;
        $listener = new ProfilerListener($profiler, $requestStack, null, $onlyException);

        // master request
        $listener->onKernelResponse(new FilterResponseEvent($kernel, $masterRequest, Kernel::MASTER_REQUEST, $response));

        // sub request
        $listener->onKernelException(new GetResponseForExceptionEvent($kernel, $subRequest, Kernel::SUB_REQUEST, new HttpException(404)));
        $listener->onKernelResponse(new FilterResponseEvent($kernel, $subRequest, Kernel::SUB_REQUEST, $response));

        $listener->onKernelTerminate(new PostResponseEvent($kernel, $masterRequest, $response));
    }
}
