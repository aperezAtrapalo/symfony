<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Templating;

use Makhan\Bundle\FrameworkBundle\Templating\PhpEngine;
use Makhan\Component\DependencyInjection\Container;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\RequestStack;
use Makhan\Component\HttpFoundation\Session\Session;
use Makhan\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Makhan\Component\Templating\TemplateNameParser;
use Makhan\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Makhan\Bundle\FrameworkBundle\Tests\TestCase;

class PhpEngineTest extends TestCase
{
    public function testEvaluateAddsAppGlobal()
    {
        $container = $this->getContainer();
        $loader = $this->getMockForAbstractClass('Makhan\Component\Templating\Loader\Loader');
        $engine = new PhpEngine(new TemplateNameParser(), $container, $loader, $app = new GlobalVariables($container));
        $globals = $engine->getGlobals();
        $this->assertSame($app, $globals['app']);
    }

    public function testEvaluateWithoutAvailableRequest()
    {
        $container = new Container();
        $loader = $this->getMockForAbstractClass('Makhan\Component\Templating\Loader\Loader');
        $engine = new PhpEngine(new TemplateNameParser(), $container, $loader, new GlobalVariables($container));

        $container->set('request_stack', null);

        $globals = $engine->getGlobals();
        $this->assertEmpty($globals['app']->getRequest());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetInvalidHelper()
    {
        $container = $this->getContainer();
        $loader = $this->getMockForAbstractClass('Makhan\Component\Templating\Loader\Loader');
        $engine = new PhpEngine(new TemplateNameParser(), $container, $loader);

        $engine->get('non-existing-helper');
    }

    /**
     * Creates a Container with a Session-containing Request service.
     *
     * @return Container
     */
    protected function getContainer()
    {
        $container = new Container();
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $stack = new RequestStack();
        $stack->push($request);

        $request->setSession($session);
        $container->set('request_stack', $stack);

        return $container;
    }
}
