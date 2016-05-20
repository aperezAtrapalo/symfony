<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\WebProfilerBundle\Tests\Controller;

use Makhan\Bundle\WebProfilerBundle\Controller\ProfilerController;
use Makhan\Component\HttpKernel\Profiler\Profile;
use Makhan\Component\HttpFoundation\Request;

class ProfilerControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getEmptyTokenCases
     */
    public function testEmptyToken($token)
    {
        $urlGenerator = $this->getMock('Makhan\Component\Routing\Generator\UrlGeneratorInterface');
        $twig = $this->getMockBuilder('Twig_Environment')->disableOriginalConstructor()->getMock();
        $profiler = $this
            ->getMockBuilder('Makhan\Component\HttpKernel\Profiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();

        $controller = new ProfilerController($urlGenerator, $profiler, $twig, array());

        $response = $controller->toolbarAction(Request::create('/_wdt/empty'), $token);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function getEmptyTokenCases()
    {
        return array(
            array(null),
            // "empty" is also a valid empty token case, see https://github.com/makhan/makhan/issues/10806
            array('empty'),
        );
    }

    public function testReturns404onTokenNotFound()
    {
        $urlGenerator = $this->getMock('Makhan\Component\Routing\Generator\UrlGeneratorInterface');
        $twig = $this->getMockBuilder('Twig_Environment')->disableOriginalConstructor()->getMock();
        $profiler = $this
            ->getMockBuilder('Makhan\Component\HttpKernel\Profiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();

        $controller = new ProfilerController($urlGenerator, $profiler, $twig, array());

        $profiler
            ->expects($this->exactly(2))
            ->method('loadProfile')
            ->will($this->returnCallback(function ($token) {
                if ('found' == $token) {
                    return new Profile($token);
                }
            }))
        ;

        $response = $controller->toolbarAction(Request::create('/_wdt/found'), 'found');
        $this->assertEquals(200, $response->getStatusCode());

        $response = $controller->toolbarAction(Request::create('/_wdt/notFound'), 'notFound');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testSearchResult()
    {
        $urlGenerator = $this->getMock('Makhan\Component\Routing\Generator\UrlGeneratorInterface');
        $twig = $this->getMockBuilder('Twig_Environment')->disableOriginalConstructor()->getMock();
        $profiler = $this
            ->getMockBuilder('Makhan\Component\HttpKernel\Profiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();

        $controller = new ProfilerController($urlGenerator, $profiler, $twig, array());

        $tokens = array(
            array(
                'token' => 'token1',
                'ip' => '127.0.0.1',
                'method' => 'GET',
                'url' => 'http://example.com/',
                'time' => 0,
                'parent' => null,
                'status_code' => 200,
            ),
            array(
                'token' => 'token2',
                'ip' => '127.0.0.1',
                'method' => 'GET',
                'url' => 'http://example.com/not_found',
                'time' => 0,
                'parent' => null,
                'status_code' => 404,
            ),
        );
        $profiler
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($tokens));

        $request = Request::create('/_profiler/empty/search/results', 'GET', array(
                'limit' => 2,
                'ip' => '127.0.0.1',
                'method' => 'GET',
                'url' => 'http://example.com/',
        ));

        $twig->expects($this->once())
            ->method('render')
            ->with($this->stringEndsWith('results.html.twig'), $this->equalTo(array(
                'token' => 'empty',
                'profile' => null,
                'tokens' => $tokens,
                'ip' => '127.0.0.1',
                'method' => 'GET',
                'status_code' => null,
                'url' => 'http://example.com/',
                'start' => null,
                'end' => null,
                'limit' => 2,
                'panel' => null,
                'request' => $request,
            )));

        $response = $controller->searchResultsAction($request, 'empty');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
