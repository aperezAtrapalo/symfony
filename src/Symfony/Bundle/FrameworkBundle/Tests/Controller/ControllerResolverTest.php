<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Controller;

use Psr\Log\LoggerInterface;
use Makhan\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Makhan\Bundle\FrameworkBundle\Controller\ControllerResolver;
use Makhan\Component\DependencyInjection\ContainerAwareInterface;
use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpKernel\Tests\Controller\ControllerResolverTest as BaseControllerResolverTest;

class ControllerResolverTest extends BaseControllerResolverTest
{
    public function testGetControllerOnContainerAware()
    {
        $resolver = $this->createControllerResolver();
        $request = Request::create('/');
        $request->attributes->set('_controller', 'Makhan\Bundle\FrameworkBundle\Tests\Controller\ContainerAwareController::testAction');

        $controller = $resolver->getController($request);

        $this->assertInstanceOf('Makhan\Component\DependencyInjection\ContainerInterface', $controller[0]->getContainer());
        $this->assertSame('testAction', $controller[1]);
    }

    public function testGetControllerOnContainerAwareInvokable()
    {
        $resolver = $this->createControllerResolver();
        $request = Request::create('/');
        $request->attributes->set('_controller', 'Makhan\Bundle\FrameworkBundle\Tests\Controller\ContainerAwareController');

        $controller = $resolver->getController($request);

        $this->assertInstanceOf('Makhan\Bundle\FrameworkBundle\Tests\Controller\ContainerAwareController', $controller);
        $this->assertInstanceOf('Makhan\Component\DependencyInjection\ContainerInterface', $controller->getContainer());
    }

    public function testGetControllerWithBundleNotation()
    {
        $shortName = 'FooBundle:Default:test';
        $parser = $this->createMockParser();
        $parser->expects($this->once())
            ->method('parse')
            ->with($shortName)
            ->will($this->returnValue('Makhan\Bundle\FrameworkBundle\Tests\Controller\ContainerAwareController::testAction'))
        ;

        $resolver = $this->createControllerResolver(null, $parser);
        $request = Request::create('/');
        $request->attributes->set('_controller', $shortName);

        $controller = $resolver->getController($request);

        $this->assertInstanceOf('Makhan\Bundle\FrameworkBundle\Tests\Controller\ContainerAwareController', $controller[0]);
        $this->assertInstanceOf('Makhan\Component\DependencyInjection\ContainerInterface', $controller[0]->getContainer());
        $this->assertSame('testAction', $controller[1]);
    }

    public function testGetControllerService()
    {
        $container = $this->createMockContainer();
        $container->expects($this->once())
            ->method('get')
            ->with('foo')
            ->will($this->returnValue($this))
        ;

        $resolver = $this->createControllerResolver(null, null, $container);
        $request = Request::create('/');
        $request->attributes->set('_controller', 'foo:controllerMethod1');

        $controller = $resolver->getController($request);

        $this->assertInstanceOf(get_class($this), $controller[0]);
        $this->assertSame('controllerMethod1', $controller[1]);
    }

    public function testGetControllerInvokableService()
    {
        $invokableController = new InvokableController('bar');

        $container = $this->createMockContainer();
        $container->expects($this->once())
            ->method('has')
            ->with('foo')
            ->will($this->returnValue(true))
        ;
        $container->expects($this->once())
            ->method('get')
            ->with('foo')
            ->will($this->returnValue($invokableController))
        ;

        $resolver = $this->createControllerResolver(null, null, $container);
        $request = Request::create('/');
        $request->attributes->set('_controller', 'foo');

        $controller = $resolver->getController($request);

        $this->assertEquals($invokableController, $controller);
    }

    public function testGetControllerInvokableServiceWithClassNameAsName()
    {
        $invokableController = new InvokableController('bar');
        $className = __NAMESPACE__.'\InvokableController';

        $container = $this->createMockContainer();
        $container->expects($this->once())
            ->method('has')
            ->with($className)
            ->will($this->returnValue(true))
        ;
        $container->expects($this->once())
            ->method('get')
            ->with($className)
            ->will($this->returnValue($invokableController))
        ;

        $resolver = $this->createControllerResolver(null, null, $container);
        $request = Request::create('/');
        $request->attributes->set('_controller', $className);

        $controller = $resolver->getController($request);

        $this->assertEquals($invokableController, $controller);
    }

    /**
     * @dataProvider getUndefinedControllers
     */
    public function testGetControllerOnNonUndefinedFunction($controller, $exceptionName = null, $exceptionMessage = null)
    {
        // All this logic needs to be duplicated, since calling parent::testGetControllerOnNonUndefinedFunction will override the expected excetion and not use the regex
        $resolver = $this->createControllerResolver();
        $this->setExpectedExceptionRegExp($exceptionName, $exceptionMessage);

        $request = Request::create('/');
        $request->attributes->set('_controller', $controller);
        $resolver->getController($request);
    }

    public function getUndefinedControllers()
    {
        return array(
            array('foo', '\LogicException', '/Unable to parse the controller name "foo"\./'),
            array('foo::bar', '\InvalidArgumentException', '/Class "foo" does not exist\./'),
            array('stdClass', '\LogicException', '/Unable to parse the controller name "stdClass"\./'),
            array(
                'Makhan\Component\HttpKernel\Tests\Controller\ControllerResolverTest::bar',
                '\InvalidArgumentException',
                '/.?[cC]ontroller(.*?) for URI "\/" is not callable\.( Expected method(.*) Available methods)?/',
            ),
        );
    }

    protected function createControllerResolver(LoggerInterface $logger = null, ControllerNameParser $parser = null, ContainerInterface $container = null)
    {
        if (!$parser) {
            $parser = $this->createMockParser();
        }

        if (!$container) {
            $container = $this->createMockContainer();
        }

        return new ControllerResolver($container, $parser, $logger);
    }

    protected function createMockParser()
    {
        return $this->getMock('Makhan\Bundle\FrameworkBundle\Controller\ControllerNameParser', array(), array(), '', false);
    }

    protected function createMockContainer()
    {
        return $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');
    }
}

class ContainerAwareController implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function testAction()
    {
    }

    public function __invoke()
    {
    }
}

class InvokableController
{
    public function __construct($bar) // mandatory argument to prevent automatic instantiation
    {
    }

    public function __invoke()
    {
    }
}
