<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests;

use Makhan\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Makhan\Component\HttpKernel\HttpKernel;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpKernel\Controller\ControllerResolverInterface;
use Makhan\Component\EventDispatcher\EventDispatcher;

class TestHttpKernel extends HttpKernel implements ControllerResolverInterface, ArgumentResolverInterface
{
    public function __construct()
    {
        parent::__construct(new EventDispatcher(), $this, null, $this);
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
        return new Response('Request: '.$request->getRequestUri());
    }
}
