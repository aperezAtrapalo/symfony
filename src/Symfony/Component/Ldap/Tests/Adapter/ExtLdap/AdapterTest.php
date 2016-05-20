<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Ldap\Tests;

use Makhan\Component\Ldap\Adapter\ExtLdap\Adapter;
use Makhan\Component\Ldap\Adapter\ExtLdap\Collection;
use Makhan\Component\Ldap\Entry;
use Makhan\Component\Ldap\LdapInterface;

/**
 * @requires extension ldap
 */
class AdapterTest extends LdapTestCase
{
    public function testLdapEscape()
    {
        $ldap = new Adapter();

        $this->assertEquals('\20foo\3dbar\0d(baz)*\20', $ldap->escape(" foo=bar\r(baz)* ", null, LdapInterface::ESCAPE_DN));
    }

    /**
     * @group functional
     */
    public function testLdapQuery()
    {
        $ldap = new Adapter($this->getLdapConfig());

        $ldap->getConnection()->bind('cn=admin,dc=makhan,dc=com', 'makhan');
        $query = $ldap->createQuery('dc=makhan,dc=com', '(&(objectclass=person)(ou=Maintainers))', array());
        $result = $query->execute();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);

        $entry = $result[0];
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals(array('Fabien Potencier'), $entry->getAttribute('cn'));
        $this->assertEquals(array('fabpot@makhan.com', 'fabien@potencier.com'), $entry->getAttribute('mail'));
    }
}
