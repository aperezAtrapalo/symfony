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
use Makhan\Component\Security\Http\Firewall\RemoteUserAuthenticationListener;

class RemoteUserAuthenticationListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPreAuthenticatedData()
    {
        $serverVars = array(
            'REMOTE_USER' => 'TheUser',
        );

        $request = new Request(array(), array(), array(), array(), array(), $serverVars);

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');

        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new RemoteUserAuthenticationListener(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey'
        );

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
        $this->assertSame($result, array('TheUser', null));
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testGetPreAuthenticatedDataNoUser()
    {
        $request = new Request(array(), array(), array(), array(), array(), array());

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');

        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new RemoteUserAuthenticationListener(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey'
        );

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
    }

    public function testGetPreAuthenticatedDataWithDifferentKeys()
    {
        $userCredentials = array('TheUser', null);

        $request = new Request(array(), array(), array(), array(), array(), array(
            'TheUserKey' => 'TheUser',
        ));
        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');

        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new RemoteUserAuthenticationListener(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey',
            'TheUserKey'
        );

        $method = new \ReflectionMethod($listener, 'getPreAuthenticatedData');
        $method->setAccessible(true);

        $result = $method->invokeArgs($listener, array($request));
        $this->assertSame($result, $userCredentials);
    }
}
