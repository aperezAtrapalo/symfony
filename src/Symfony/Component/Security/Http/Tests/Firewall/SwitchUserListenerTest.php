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

use Makhan\Component\Security\Http\Event\SwitchUserEvent;
use Makhan\Component\Security\Http\Firewall\SwitchUserListener;
use Makhan\Component\Security\Http\SecurityEvents;

class SwitchUserListenerTest extends \PHPUnit_Framework_TestCase
{
    private $tokenStorage;

    private $userProvider;

    private $userChecker;

    private $accessDecisionManager;

    private $request;

    private $event;

    protected function setUp()
    {
        $this->tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $this->userProvider = $this->getMock('Makhan\Component\Security\Core\User\UserProviderInterface');
        $this->userChecker = $this->getMock('Makhan\Component\Security\Core\User\UserCheckerInterface');
        $this->accessDecisionManager = $this->getMock('Makhan\Component\Security\Core\Authorization\AccessDecisionManagerInterface');
        $this->request = $this->getMock('Makhan\Component\HttpFoundation\Request');
        $this->request->query = $this->getMock('Makhan\Component\HttpFoundation\ParameterBag');
        $this->request->server = $this->getMock('Makhan\Component\HttpFoundation\ServerBag');
        $this->event = $this->getEvent($this->request);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $providerKey must not be empty
     */
    public function testProviderKeyIsRequired()
    {
        new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, '', $this->accessDecisionManager);
    }

    public function testEventIsIgnoredIfUsernameIsNotPassedWithTheRequest()
    {
        $this->request->expects($this->any())->method('get')->with('_switch_user')->will($this->returnValue(null));

        $this->event->expects($this->never())->method('setResponse');
        $this->tokenStorage->expects($this->never())->method('setToken');

        $listener = new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, 'provider123', $this->accessDecisionManager);
        $listener->handle($this->event);
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException
     */
    public function testExitUserThrowsAuthenticationExceptionIfOriginalTokenCannotBeFound()
    {
        $token = $this->getToken(array($this->getMock('Makhan\Component\Security\Core\Role\RoleInterface')));

        $this->tokenStorage->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $this->request->expects($this->any())->method('get')->with('_switch_user')->will($this->returnValue('_exit'));

        $listener = new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, 'provider123', $this->accessDecisionManager);
        $listener->handle($this->event);
    }

    public function testExitUserUpdatesToken()
    {
        $originalToken = $this->getToken();
        $role = $this->getMockBuilder('Makhan\Component\Security\Core\Role\SwitchUserRole')
            ->disableOriginalConstructor()
            ->getMock();
        $role->expects($this->any())->method('getSource')->will($this->returnValue($originalToken));

        $this->tokenStorage->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($this->getToken(array($role))));

        $this->request->expects($this->any())->method('get')->with('_switch_user')->will($this->returnValue('_exit'));
        $this->request->expects($this->any())->method('getUri')->will($this->returnValue('/'));
        $this->request->query->expects($this->once())->method('remove', '_switch_user');
        $this->request->query->expects($this->any())->method('all')->will($this->returnValue(array()));
        $this->request->server->expects($this->once())->method('set')->with('QUERY_STRING', '');

        $this->tokenStorage->expects($this->once())
            ->method('setToken')->with($originalToken);
        $this->event->expects($this->once())
            ->method('setResponse')->with($this->isInstanceOf('Makhan\Component\HttpFoundation\RedirectResponse'));

        $listener = new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, 'provider123', $this->accessDecisionManager);
        $listener->handle($this->event);
    }

    public function testExitUserDispatchesEventWithRefreshedUser()
    {
        $originalUser = $this->getMock('Makhan\Component\Security\Core\User\UserInterface');
        $refreshedUser = $this->getMock('Makhan\Component\Security\Core\User\UserInterface');
        $this
            ->userProvider
            ->expects($this->any())
            ->method('refreshUser')
            ->with($originalUser)
            ->willReturn($refreshedUser);
        $originalToken = $this->getToken();
        $originalToken
            ->expects($this->any())
            ->method('getUser')
            ->willReturn($originalUser);
        $role = $this
            ->getMockBuilder('Makhan\Component\Security\Core\Role\SwitchUserRole')
            ->disableOriginalConstructor()
            ->getMock();
        $role->expects($this->any())->method('getSource')->willReturn($originalToken);
        $this
            ->tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->willReturn($this->getToken(array($role)));
        $this
            ->request
            ->expects($this->any())
            ->method('get')
            ->with('_switch_user')
            ->willReturn('_exit');
        $this
            ->request
            ->expects($this->any())
            ->method('getUri')
            ->willReturn('/');
        $this
            ->request
            ->query
            ->expects($this->any())
            ->method('all')
            ->will($this->returnValue(array()));

        $dispatcher = $this->getMock('Makhan\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(SecurityEvents::SWITCH_USER, $this->callback(function (SwitchUserEvent $event) use ($refreshedUser) {
                return $event->getTargetUser() === $refreshedUser;
            }))
        ;

        $listener = new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, 'provider123', $this->accessDecisionManager, null, '_switch_user', 'ROLE_ALLOWED_TO_SWITCH', $dispatcher);
        $listener->handle($this->event);
    }

    public function testExitUserDoesNotDispatchEventWithStringUser()
    {
        $originalUser = 'anon.';
        $this
            ->userProvider
            ->expects($this->never())
            ->method('refreshUser');
        $originalToken = $this->getToken();
        $originalToken
            ->expects($this->any())
            ->method('getUser')
            ->willReturn($originalUser);
        $role = $this
            ->getMockBuilder('Makhan\Component\Security\Core\Role\SwitchUserRole')
            ->disableOriginalConstructor()
            ->getMock();
        $role
            ->expects($this->any())
            ->method('getSource')
            ->willReturn($originalToken);
        $this
            ->tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->willReturn($this->getToken(array($role)));
        $this
            ->request
            ->expects($this->any())
            ->method('get')
            ->with('_switch_user')
            ->willReturn('_exit');
        $this
            ->request
            ->query
            ->expects($this->any())
            ->method('all')
            ->will($this->returnValue(array()));
        $this
            ->request
            ->expects($this->any())
            ->method('getUri')
            ->willReturn('/');

        $dispatcher = $this->getMock('Makhan\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher
            ->expects($this->never())
            ->method('dispatch')
        ;

        $listener = new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, 'provider123', $this->accessDecisionManager, null, '_switch_user', 'ROLE_ALLOWED_TO_SWITCH', $dispatcher);
        $listener->handle($this->event);
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testSwitchUserIsDisallowed()
    {
        $token = $this->getToken(array($this->getMock('Makhan\Component\Security\Core\Role\RoleInterface')));

        $this->tokenStorage->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $this->request->expects($this->any())->method('get')->with('_switch_user')->will($this->returnValue('kuba'));

        $this->accessDecisionManager->expects($this->once())
            ->method('decide')->with($token, array('ROLE_ALLOWED_TO_SWITCH'))
            ->will($this->returnValue(false));

        $listener = new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, 'provider123', $this->accessDecisionManager);
        $listener->handle($this->event);
    }

    public function testSwitchUser()
    {
        $token = $this->getToken(array($this->getMock('Makhan\Component\Security\Core\Role\RoleInterface')));
        $user = $this->getMock('Makhan\Component\Security\Core\User\UserInterface');
        $user->expects($this->any())->method('getRoles')->will($this->returnValue(array()));

        $this->tokenStorage->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $this->request->expects($this->any())->method('get')->with('_switch_user')->will($this->returnValue('kuba'));
        $this->request->query->expects($this->once())->method('remove', '_switch_user');
        $this->request->query->expects($this->any())->method('all')->will($this->returnValue(array()));

        $this->request->expects($this->any())->method('getUri')->will($this->returnValue('/'));
        $this->request->server->expects($this->once())->method('set')->with('QUERY_STRING', '');

        $this->accessDecisionManager->expects($this->once())
            ->method('decide')->with($token, array('ROLE_ALLOWED_TO_SWITCH'))
            ->will($this->returnValue(true));

        $this->userProvider->expects($this->once())
            ->method('loadUserByUsername')->with('kuba')
            ->will($this->returnValue($user));
        $this->userChecker->expects($this->once())
            ->method('checkPostAuth')->with($user);
        $this->tokenStorage->expects($this->once())
            ->method('setToken')->with($this->isInstanceOf('Makhan\Component\Security\Core\Authentication\Token\UsernamePasswordToken'));

        $listener = new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, 'provider123', $this->accessDecisionManager);
        $listener->handle($this->event);
    }

    public function testSwitchUserKeepsOtherQueryStringParameters()
    {
        $token = $this->getToken(array($this->getMock('Makhan\Component\Security\Core\Role\RoleInterface')));
        $user = $this->getMock('Makhan\Component\Security\Core\User\UserInterface');
        $user->expects($this->any())->method('getRoles')->will($this->returnValue(array()));

        $this->tokenStorage->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $this->request->expects($this->any())->method('get')->with('_switch_user')->will($this->returnValue('kuba'));
        $this->request->query->expects($this->once())->method('remove', '_switch_user');
        $this->request->query->expects($this->any())->method('all')->will($this->returnValue(array('page' => 3, 'section' => 2)));
        $this->request->expects($this->any())->method('getUri')->will($this->returnValue('/'));
        $this->request->server->expects($this->once())->method('set')->with('QUERY_STRING', 'page=3&section=2');

        $this->accessDecisionManager->expects($this->once())
            ->method('decide')->with($token, array('ROLE_ALLOWED_TO_SWITCH'))
            ->will($this->returnValue(true));

        $this->userProvider->expects($this->once())
            ->method('loadUserByUsername')->with('kuba')
            ->will($this->returnValue($user));
        $this->userChecker->expects($this->once())
            ->method('checkPostAuth')->with($user);
        $this->tokenStorage->expects($this->once())
            ->method('setToken')->with($this->isInstanceOf('Makhan\Component\Security\Core\Authentication\Token\UsernamePasswordToken'));

        $listener = new SwitchUserListener($this->tokenStorage, $this->userProvider, $this->userChecker, 'provider123', $this->accessDecisionManager);
        $listener->handle($this->event);
    }

    private function getEvent($request)
    {
        $event = $this->getMockBuilder('Makhan\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        return $event;
    }

    private function getToken(array $roles = array())
    {
        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())
            ->method('getRoles')
            ->will($this->returnValue($roles));

        return $token;
    }
}
