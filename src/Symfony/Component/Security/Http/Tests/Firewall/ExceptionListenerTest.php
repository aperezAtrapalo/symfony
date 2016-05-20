<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Tests\Firewall;

use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Makhan\Component\HttpKernel\HttpKernelInterface;
use Makhan\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Makhan\Component\Security\Core\Exception\AccessDeniedException;
use Makhan\Component\Security\Core\Exception\AuthenticationException;
use Makhan\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Makhan\Component\Security\Http\Firewall\ExceptionListener;
use Makhan\Component\Security\Http\HttpUtils;

class ExceptionListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getAuthenticationExceptionProvider
     */
    public function testAuthenticationExceptionWithoutEntryPoint(\Exception $exception, \Exception $eventException = null)
    {
        $event = $this->createEvent($exception);

        $listener = $this->createExceptionListener();
        $listener->onKernelException($event);

        $this->assertNull($event->getResponse());
        $this->assertSame(null === $eventException ? $exception : $eventException, $event->getException());
    }

    /**
     * @dataProvider getAuthenticationExceptionProvider
     */
    public function testAuthenticationExceptionWithEntryPoint(\Exception $exception, \Exception $eventException = null)
    {
        $event = $this->createEvent($exception = new AuthenticationException());

        $listener = $this->createExceptionListener(null, null, null, $this->createEntryPoint());
        $listener->onKernelException($event);

        $this->assertEquals('OK', $event->getResponse()->getContent());
        $this->assertSame($exception, $event->getException());
    }

    public function getAuthenticationExceptionProvider()
    {
        return array(
            array(new AuthenticationException()),
            array(new \LogicException('random', 0, $e = new AuthenticationException()), $e),
            array(new \LogicException('random', 0, $e = new AuthenticationException('embed', 0, new AuthenticationException())), $e),
            array(new \LogicException('random', 0, $e = new AuthenticationException('embed', 0, new AccessDeniedException())), $e),
            array(new AuthenticationException('random', 0, new \LogicException())),
        );
    }

    public function testExceptionWhenEntryPointReturnsBadValue()
    {
        $event = $this->createEvent(new AuthenticationException());

        $entryPoint = $this->getMock('Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface');
        $entryPoint->expects($this->once())->method('start')->will($this->returnValue('NOT A RESPONSE'));

        $listener = $this->createExceptionListener(null, null, null, $entryPoint);
        $listener->onKernelException($event);
        // the exception has been replaced by our LogicException
        $this->assertInstanceOf('LogicException', $event->getException());
        $this->assertStringEndsWith('start() method must return a Response object (string returned)', $event->getException()->getMessage());
    }

    /**
     * @dataProvider getAccessDeniedExceptionProvider
     */
    public function testAccessDeniedExceptionFullFledgedAndWithoutAccessDeniedHandlerAndWithoutErrorPage(\Exception $exception, \Exception $eventException = null)
    {
        $event = $this->createEvent($exception);

        $listener = $this->createExceptionListener(null, $this->createTrustResolver(true));
        $listener->onKernelException($event);

        $this->assertNull($event->getResponse());
        $this->assertSame(null === $eventException ? $exception : $eventException, $event->getException()->getPrevious());
    }

    /**
     * @dataProvider getAccessDeniedExceptionProvider
     */
    public function testAccessDeniedExceptionFullFledgedAndWithoutAccessDeniedHandlerAndWithErrorPage(\Exception $exception, \Exception $eventException = null)
    {
        $kernel = $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface');
        $kernel->expects($this->once())->method('handle')->will($this->returnValue(new Response('error')));

        $event = $this->createEvent($exception, $kernel);

        $httpUtils = $this->getMock('Makhan\Component\Security\Http\HttpUtils');
        $httpUtils->expects($this->once())->method('createRequest')->will($this->returnValue(Request::create('/error')));

        $listener = $this->createExceptionListener(null, $this->createTrustResolver(true), $httpUtils, null, '/error');
        $listener->onKernelException($event);

        $this->assertEquals('error', $event->getResponse()->getContent());
        $this->assertSame(null === $eventException ? $exception : $eventException, $event->getException()->getPrevious());
    }

    /**
     * @dataProvider getAccessDeniedExceptionProvider
     */
    public function testAccessDeniedExceptionFullFledgedAndWithAccessDeniedHandlerAndWithoutErrorPage(\Exception $exception, \Exception $eventException = null)
    {
        $event = $this->createEvent($exception);

        $accessDeniedHandler = $this->getMock('Makhan\Component\Security\Http\Authorization\AccessDeniedHandlerInterface');
        $accessDeniedHandler->expects($this->once())->method('handle')->will($this->returnValue(new Response('error')));

        $listener = $this->createExceptionListener(null, $this->createTrustResolver(true), null, null, null, $accessDeniedHandler);
        $listener->onKernelException($event);

        $this->assertEquals('error', $event->getResponse()->getContent());
        $this->assertSame(null === $eventException ? $exception : $eventException, $event->getException()->getPrevious());
    }

    /**
     * @dataProvider getAccessDeniedExceptionProvider
     */
    public function testAccessDeniedExceptionNotFullFledged(\Exception $exception, \Exception $eventException = null)
    {
        $event = $this->createEvent($exception);

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage->expects($this->once())->method('getToken')->will($this->returnValue($this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface')));

        $listener = $this->createExceptionListener($tokenStorage, $this->createTrustResolver(false), null, $this->createEntryPoint());
        $listener->onKernelException($event);

        $this->assertEquals('OK', $event->getResponse()->getContent());
        $this->assertSame(null === $eventException ? $exception : $eventException, $event->getException()->getPrevious());
    }

    public function getAccessDeniedExceptionProvider()
    {
        return array(
            array(new AccessDeniedException()),
            array(new \LogicException('random', 0, $e = new AccessDeniedException()), $e),
            array(new \LogicException('random', 0, $e = new AccessDeniedException('embed', new AccessDeniedException())), $e),
            array(new \LogicException('random', 0, $e = new AccessDeniedException('embed', new AuthenticationException())), $e),
            array(new AccessDeniedException('random', new \LogicException())),
        );
    }

    private function createEntryPoint()
    {
        $entryPoint = $this->getMock('Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface');
        $entryPoint->expects($this->once())->method('start')->will($this->returnValue(new Response('OK')));

        return $entryPoint;
    }

    private function createTrustResolver($fullFledged)
    {
        $trustResolver = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface');
        $trustResolver->expects($this->once())->method('isFullFledged')->will($this->returnValue($fullFledged));

        return $trustResolver;
    }

    private function createEvent(\Exception $exception, $kernel = null)
    {
        if (null === $kernel) {
            $kernel = $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface');
        }

        return new GetResponseForExceptionEvent($kernel, Request::create('/'), HttpKernelInterface::MASTER_REQUEST, $exception);
    }

    private function createExceptionListener(TokenStorageInterface $tokenStorage = null, AuthenticationTrustResolverInterface $trustResolver = null, HttpUtils $httpUtils = null, AuthenticationEntryPointInterface $authenticationEntryPoint = null, $errorPage = null, AccessDeniedHandlerInterface $accessDeniedHandler = null)
    {
        return new ExceptionListener(
            $tokenStorage ?: $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'),
            $trustResolver ?: $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface'),
            $httpUtils ?: $this->getMock('Makhan\Component\Security\Http\HttpUtils'),
            'key',
            $authenticationEntryPoint,
            $errorPage,
            $accessDeniedHandler
        );
    }
}
