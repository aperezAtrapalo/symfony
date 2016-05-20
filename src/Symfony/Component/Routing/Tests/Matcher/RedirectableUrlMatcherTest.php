<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Routing\Tests\Matcher;

use Makhan\Component\Routing\Route;
use Makhan\Component\Routing\RouteCollection;
use Makhan\Component\Routing\RequestContext;

class RedirectableUrlMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testRedirectWhenNoSlash()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo/'));

        $matcher = $this->getMockForAbstractClass('Makhan\Component\Routing\Matcher\RedirectableUrlMatcher', array($coll, new RequestContext()));
        $matcher->expects($this->once())->method('redirect');
        $matcher->match('/foo');
    }

    /**
     * @expectedException \Makhan\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testRedirectWhenNoSlashForNonSafeMethod()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo/'));

        $context = new RequestContext();
        $context->setMethod('POST');
        $matcher = $this->getMockForAbstractClass('Makhan\Component\Routing\Matcher\RedirectableUrlMatcher', array($coll, $context));
        $matcher->match('/foo');
    }

    public function testSchemeRedirectRedirectsToFirstScheme()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo', array(), array(), array(), '', array('FTP', 'HTTPS')));

        $matcher = $this->getMockForAbstractClass('Makhan\Component\Routing\Matcher\RedirectableUrlMatcher', array($coll, new RequestContext()));
        $matcher
            ->expects($this->once())
            ->method('redirect')
            ->with('/foo', 'foo', 'ftp')
            ->will($this->returnValue(array('_route' => 'foo')))
        ;
        $matcher->match('/foo');
    }

    public function testNoSchemaRedirectIfOnOfMultipleSchemesMatches()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo', array(), array(), array(), '', array('https', 'http')));

        $matcher = $this->getMockForAbstractClass('Makhan\Component\Routing\Matcher\RedirectableUrlMatcher', array($coll, new RequestContext()));
        $matcher
            ->expects($this->never())
            ->method('redirect')
        ;
        $matcher->match('/foo');
    }
}
