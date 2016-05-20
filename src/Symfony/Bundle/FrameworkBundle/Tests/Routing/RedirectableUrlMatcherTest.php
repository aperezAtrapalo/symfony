<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Routing;

use Makhan\Component\Routing\Route;
use Makhan\Component\Routing\RouteCollection;
use Makhan\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher;
use Makhan\Component\Routing\RequestContext;

class RedirectableUrlMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testRedirectWhenNoSlash()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo/'));

        $matcher = new RedirectableUrlMatcher($coll, $context = new RequestContext());

        $this->assertEquals(array(
                '_controller' => 'Makhan\Bundle\FrameworkBundle\Controller\RedirectController::urlRedirectAction',
                'path' => '/foo/',
                'permanent' => true,
                'scheme' => null,
                'httpPort' => $context->getHttpPort(),
                'httpsPort' => $context->getHttpsPort(),
                '_route' => null,
            ),
            $matcher->match('/foo')
        );
    }

    public function testSchemeRedirect()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo', array(), array(), array(), '', array('https')));

        $matcher = new RedirectableUrlMatcher($coll, $context = new RequestContext());

        $this->assertEquals(array(
                '_controller' => 'Makhan\Bundle\FrameworkBundle\Controller\RedirectController::urlRedirectAction',
                'path' => '/foo',
                'permanent' => true,
                'scheme' => 'https',
                'httpPort' => $context->getHttpPort(),
                'httpsPort' => $context->getHttpsPort(),
                '_route' => 'foo',
            ),
            $matcher->match('/foo')
        );
    }
}
