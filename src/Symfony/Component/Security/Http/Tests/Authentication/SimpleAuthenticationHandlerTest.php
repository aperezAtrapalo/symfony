<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Tests;

use Makhan\Component\Security\Core\Authentication\SimpleAuthenticatorInterface;
use Makhan\Component\Security\Core\Exception\AuthenticationException;
use Makhan\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Makhan\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Makhan\Component\Security\Http\Authentication\SimpleAuthenticationHandler;

class SimpleAuthenticationHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $successHandler;

    private $failureHandler;

    private $request;

    private $token;

    private $authenticationException;

    private $response;

    protected function setUp()
    {
        $this->successHandler = $this->getMock('Makhan\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface');
        $this->failureHandler = $this->getMock('Makhan\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface');

        $this->request = $this->getMock('Makhan\Component\HttpFoundation\Request');
        $this->token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
        // No methods are invoked on the exception; we just assert on its class
        $this->authenticationException = new AuthenticationException();

        $this->response = $this->getMock('Makhan\Component\HttpFoundation\Response');
    }

    public function testOnAuthenticationSuccessFallsBackToDefaultHandlerIfSimpleIsNotASuccessHandler()
    {
        $authenticator = $this->getMock('Makhan\Component\Security\Core\Authentication\SimpleAuthenticatorInterface');

        $this->successHandler->expects($this->once())
            ->method('onAuthenticationSuccess')
            ->with($this->request, $this->token)
            ->will($this->returnValue($this->response));

        $handler = new SimpleAuthenticationHandler($authenticator, $this->successHandler, $this->failureHandler);
        $result = $handler->onAuthenticationSuccess($this->request, $this->token);

        $this->assertSame($this->response, $result);
    }

    public function testOnAuthenticationSuccessCallsSimpleAuthenticator()
    {
        $this->successHandler->expects($this->never())
            ->method('onAuthenticationSuccess');

        $authenticator = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Tests\TestSuccessHandlerInterface');
        $authenticator->expects($this->once())
            ->method('onAuthenticationSuccess')
            ->with($this->request, $this->token)
            ->will($this->returnValue($this->response));

        $handler = new SimpleAuthenticationHandler($authenticator, $this->successHandler, $this->failureHandler);
        $result = $handler->onAuthenticationSuccess($this->request, $this->token);

        $this->assertSame($this->response, $result);
    }

    /**
     * @expectedException        \UnexpectedValueException
     * @expectedExceptionMessage onAuthenticationSuccess method must return null to use the default success handler, or a Response object
     */
    public function testOnAuthenticationSuccessThrowsAnExceptionIfNonResponseIsReturned()
    {
        $this->successHandler->expects($this->never())
            ->method('onAuthenticationSuccess');

        $authenticator = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Tests\TestSuccessHandlerInterface');
        $authenticator->expects($this->once())
            ->method('onAuthenticationSuccess')
            ->with($this->request, $this->token)
            ->will($this->returnValue(new \stdClass()));

        $handler = new SimpleAuthenticationHandler($authenticator, $this->successHandler, $this->failureHandler);
        $handler->onAuthenticationSuccess($this->request, $this->token);
    }

    public function testOnAuthenticationSuccessFallsBackToDefaultHandlerIfNullIsReturned()
    {
        $this->successHandler->expects($this->once())
            ->method('onAuthenticationSuccess')
            ->with($this->request, $this->token)
            ->will($this->returnValue($this->response));

        $authenticator = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Tests\TestSuccessHandlerInterface');
        $authenticator->expects($this->once())
            ->method('onAuthenticationSuccess')
            ->with($this->request, $this->token)
            ->will($this->returnValue(null));

        $handler = new SimpleAuthenticationHandler($authenticator, $this->successHandler, $this->failureHandler);
        $result = $handler->onAuthenticationSuccess($this->request, $this->token);

        $this->assertSame($this->response, $result);
    }

    public function testOnAuthenticationFailureFallsBackToDefaultHandlerIfSimpleIsNotAFailureHandler()
    {
        $authenticator = $this->getMock('Makhan\Component\Security\Core\Authentication\SimpleAuthenticatorInterface');

        $this->failureHandler->expects($this->once())
            ->method('onAuthenticationFailure')
            ->with($this->request, $this->authenticationException)
            ->will($this->returnValue($this->response));

        $handler = new SimpleAuthenticationHandler($authenticator, $this->successHandler, $this->failureHandler);
        $result = $handler->onAuthenticationFailure($this->request, $this->authenticationException);

        $this->assertSame($this->response, $result);
    }

    public function testOnAuthenticationFailureCallsSimpleAuthenticator()
    {
        $this->failureHandler->expects($this->never())
            ->method('onAuthenticationFailure');

        $authenticator = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Tests\TestFailureHandlerInterface');
        $authenticator->expects($this->once())
            ->method('onAuthenticationFailure')
            ->with($this->request, $this->authenticationException)
            ->will($this->returnValue($this->response));

        $handler = new SimpleAuthenticationHandler($authenticator, $this->successHandler, $this->failureHandler);
        $result = $handler->onAuthenticationFailure($this->request, $this->authenticationException);

        $this->assertSame($this->response, $result);
    }

    /**
     * @expectedException        \UnexpectedValueException
     * @expectedExceptionMessage onAuthenticationFailure method must return null to use the default failure handler, or a Response object
     */
    public function testOnAuthenticationFailureThrowsAnExceptionIfNonResponseIsReturned()
    {
        $this->failureHandler->expects($this->never())
            ->method('onAuthenticationFailure');

        $authenticator = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Tests\TestFailureHandlerInterface');
        $authenticator->expects($this->once())
            ->method('onAuthenticationFailure')
            ->with($this->request, $this->authenticationException)
            ->will($this->returnValue(new \stdClass()));

        $handler = new SimpleAuthenticationHandler($authenticator, $this->successHandler, $this->failureHandler);
        $handler->onAuthenticationFailure($this->request, $this->authenticationException);
    }

    public function testOnAuthenticationFailureFallsBackToDefaultHandlerIfNullIsReturned()
    {
        $this->failureHandler->expects($this->once())
            ->method('onAuthenticationFailure')
            ->with($this->request, $this->authenticationException)
            ->will($this->returnValue($this->response));

        $authenticator = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Tests\TestFailureHandlerInterface');
        $authenticator->expects($this->once())
            ->method('onAuthenticationFailure')
            ->with($this->request, $this->authenticationException)
            ->will($this->returnValue(null));

        $handler = new SimpleAuthenticationHandler($authenticator, $this->successHandler, $this->failureHandler);
        $result = $handler->onAuthenticationFailure($this->request, $this->authenticationException);

        $this->assertSame($this->response, $result);
    }
}

interface TestSuccessHandlerInterface extends AuthenticationSuccessHandlerInterface, SimpleAuthenticatorInterface
{
}

interface TestFailureHandlerInterface extends AuthenticationFailureHandlerInterface, SimpleAuthenticatorInterface
{
}
