<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Tests\User;

use Makhan\Component\Ldap\Adapter\CollectionInterface;
use Makhan\Component\Ldap\Adapter\QueryInterface;
use Makhan\Component\Ldap\Entry;
use Makhan\Component\Ldap\LdapInterface;
use Makhan\Component\Security\Core\User\LdapUserProvider;
use Makhan\Component\Ldap\Exception\ConnectionException;

/**
 * @requires extension ldap
 */
class LdapUserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameFailsIfCantConnectToLdap()
    {
        $ldap = $this->getMock(LdapInterface::class);
        $ldap
            ->expects($this->once())
            ->method('bind')
            ->will($this->throwException(new ConnectionException()))
        ;

        $provider = new LdapUserProvider($ldap, 'ou=MyBusiness,dc=makhan,dc=com');
        $provider->loadUserByUsername('foo');
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameFailsIfNoLdapEntries()
    {
        $result = $this->getMock(CollectionInterface::class);
        $query = $this->getMock(QueryInterface::class);
        $query
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($result))
        ;
        $result
            ->expects($this->once())
            ->method('count')
            ->will($this->returnValue(0))
        ;
        $ldap = $this->getMock(LdapInterface::class);
        $ldap
            ->expects($this->once())
            ->method('escape')
            ->will($this->returnValue('foo'))
        ;
        $ldap
            ->expects($this->once())
            ->method('query')
            ->will($this->returnValue($query))
        ;

        $provider = new LdapUserProvider($ldap, 'ou=MyBusiness,dc=makhan,dc=com');
        $provider->loadUserByUsername('foo');
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameFailsIfMoreThanOneLdapEntry()
    {
        $result = $this->getMock(CollectionInterface::class);
        $query = $this->getMock(QueryInterface::class);
        $query
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($result))
        ;
        $result
            ->expects($this->once())
            ->method('count')
            ->will($this->returnValue(2))
        ;
        $ldap = $this->getMock(LdapInterface::class);
        $ldap
            ->expects($this->once())
            ->method('escape')
            ->will($this->returnValue('foo'))
        ;
        $ldap
            ->expects($this->once())
            ->method('query')
            ->will($this->returnValue($query))
        ;

        $provider = new LdapUserProvider($ldap, 'ou=MyBusiness,dc=makhan,dc=com');
        $provider->loadUserByUsername('foo');
    }

    public function testSuccessfulLoadUserByUsername()
    {
        $result = $this->getMock(CollectionInterface::class);
        $query = $this->getMock(QueryInterface::class);
        $query
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($result))
        ;
        $ldap = $this->getMock(LdapInterface::class);
        $result
            ->expects($this->once())
            ->method('offsetGet')
            ->with(0)
            ->will($this->returnValue(new Entry('foo', array(
                    'sAMAccountName' => 'foo',
                    'userpassword' => 'bar',
                )
            )))
        ;
        $result
            ->expects($this->once())
            ->method('count')
            ->will($this->returnValue(1))
        ;
        $ldap
            ->expects($this->once())
            ->method('escape')
            ->will($this->returnValue('foo'))
        ;
        $ldap
            ->expects($this->once())
            ->method('query')
            ->will($this->returnValue($query))
        ;

        $provider = new LdapUserProvider($ldap, 'ou=MyBusiness,dc=makhan,dc=com');
        $this->assertInstanceOf(
            'Makhan\Component\Security\Core\User\User',
            $provider->loadUserByUsername('foo')
        );
    }
}
