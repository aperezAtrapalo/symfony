<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle\Tests\Controller;

use Makhan\Bundle\TwigBundle\Controller\PreviewErrorController;
use Makhan\Bundle\TwigBundle\Tests\TestCase;
use Makhan\Component\Debug\Exception\FlattenException;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpKernel\HttpKernelInterface;

class PreviewErrorControllerTest extends TestCase
{
    public function testForwardRequestToConfiguredController()
    {
        $request = Request::create('whatever');
        $response = new Response('');
        $code = 123;
        $logicalControllerName = 'foo:bar:baz';

        $kernel = $this->getMock('\Makhan\Component\HttpKernel\HttpKernelInterface');
        $kernel
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->callback(function (Request $request) use ($logicalControllerName, $code) {

                    $this->assertEquals($logicalControllerName, $request->attributes->get('_controller'));

                    $exception = $request->attributes->get('exception');
                    $this->assertInstanceOf(FlattenException::class, $exception);
                    $this->assertEquals($code, $exception->getStatusCode());
                    $this->assertFalse($request->attributes->get('showException'));

                    return true;
                }),
                $this->equalTo(HttpKernelInterface::SUB_REQUEST)
            )
            ->will($this->returnValue($response));

        $controller = new PreviewErrorController($kernel, $logicalControllerName);

        $this->assertSame($response, $controller->previewErrorPageAction($request, $code));
    }
}
