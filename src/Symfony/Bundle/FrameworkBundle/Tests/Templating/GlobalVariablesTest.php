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

use Makhan\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Makhan\Bundle\FrameworkBundle\Tests\TestCase;
use Makhan\Component\DependencyInjection\Container;

class GlobalVariablesTest extends TestCase
{
    private $container;
    private $globals;

    protected function setUp()
    {
        $this->container = new Container();
        $this->globals = new GlobalVariables($this->container);
    }

    public function testGetUserNoTokenStorage()
    {
        $this->assertNull($this->globals->getUser());
    }

    public function testGetUserNoToken()
    {
        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $this->container->set('security.token_storage', $tokenStorage);
        $this->assertNull($this->globals->getUser());
    }

    /**
     * @dataProvider getUserProvider
     */
    public function testGetUser($user, $expectedUser)
    {
        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');

        $this->container->set('security.token_storage', $tokenStorage);

        $token
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));

        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($token));

        $this->assertSame($expectedUser, $this->globals->getUser());
    }

    public function getUserProvider()
    {
        $user = $this->getMock('Makhan\Component\Security\Core\User\UserInterface');
        $std = new \stdClass();
        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');

        return array(
            array($user, $user),
            array($std, $std),
            array($token, $token),
            array('Anon.', null),
            array(null, null),
            array(10, null),
            array(true, null),
        );
    }
}
