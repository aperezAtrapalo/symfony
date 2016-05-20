<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Tests\Authentication\Token;

use Makhan\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Makhan\Component\Security\Core\Role\Role;

class UsernamePasswordTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $token = new UsernamePasswordToken('foo', 'bar', 'key');
        $this->assertFalse($token->isAuthenticated());

        $token = new UsernamePasswordToken('foo', 'bar', 'key', array('ROLE_FOO'));
        $this->assertEquals(array(new Role('ROLE_FOO')), $token->getRoles());
        $this->assertTrue($token->isAuthenticated());
        $this->assertEquals('key', $token->getProviderKey());
    }

    /**
     * @expectedException \LogicException
     */
    public function testSetAuthenticatedToTrue()
    {
        $token = new UsernamePasswordToken('foo', 'bar', 'key');
        $token->setAuthenticated(true);
    }

    public function testSetAuthenticatedToFalse()
    {
        $token = new UsernamePasswordToken('foo', 'bar', 'key');
        $token->setAuthenticated(false);
        $this->assertFalse($token->isAuthenticated());
    }

    public function testEraseCredentials()
    {
        $token = new UsernamePasswordToken('foo', 'bar', 'key');
        $token->eraseCredentials();
        $this->assertEquals('', $token->getCredentials());
    }

    public function testToString()
    {
        $token = new UsernamePasswordToken('foo', '', 'foo', array('A', 'B'));
        $this->assertEquals('UsernamePasswordToken(user="foo", authenticated=true, roles="A, B")', (string) $token);
    }
}
