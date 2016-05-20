<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\HttpCache;

use Makhan\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Makhan\Component\HttpKernel\HttpKernel;
use Makhan\Component\HttpKernel\HttpKernelInterface;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpKernel\Controller\ControllerResolverInterface;
use Makhan\Component\EventDispatcher\EventDispatcher;

class TestHttpKernel extends HttpKernel implements ControllerResolverInterface, ArgumentResolverInterface
{
    protected $body;
    protected $status;
    protected $headers;
    protected $called = false;
    protected $customizer;
    protected $catch = false;
    protected $backendRequest;

    public function __construct($body, $status, $headers, \Closure $customizer = null)
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;
        $this->customizer = $customizer;

        parent::__construct(new EventDispatcher(), $this, null, $this);
    }

    public function getBackendRequest()
    {
        return $this->backendRequest;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = false)
    {
        $this->catch = $catch;
        $this->backendRequest = $request;

        return parent::handle($request, $type, $catch);
    }

    public function isCatchingExceptions()
    {
        return $this->catch;
    }

    public function getController(Request $request)
    {
        return array($this, 'callController');
    }

    public function getArguments(Request $request, $controller)
    {
        return array($request);
    }

    public function callController(Request $request)
    {
        $this->called = true;

        $response = new Response($this->body, $this->status, $this->headers);

        if (null !== $customizer = $this->customizer) {
            $customizer($request, $response);
        }

        return $response;
    }

    public function hasBeenCalled()
    {
        return $this->called;
    }

    public function reset()
    {
        $this->called = false;
    }
}
