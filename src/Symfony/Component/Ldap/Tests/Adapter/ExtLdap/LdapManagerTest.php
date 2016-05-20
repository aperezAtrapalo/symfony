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
use Makhan\Component\Ldap\Exception\LdapException;

/**
 * @requires extension ldap
 */
class LdapManagerTest extends LdapTestCase
{
    /** @var Adapter */
    private $adapter;

    protected function setUp()
    {
        $this->adapter = new Adapter($this->getLdapConfig());
        $this->adapter->getConnection()->bind('cn=admin,dc=makhan,dc=com', 'makhan');
    }

    /**
     * @group functional
     */
    public function testLdapAddAndRemove()
    {
        $this->executeSearchQuery(1);

        $entry = new Entry('cn=Charles Sarrazin,dc=makhan,dc=com', array(
            'sn' => array('csarrazi'),
            'objectclass' => array(
                'inetOrgPerson',
            ),
        ));

        $em = $this->adapter->getEntryManager();
        $em->add($entry);

        $this->executeSearchQuery(2);

        $em->remove($entry);
        $this->executeSearchQuery(1);
    }

    /**
     * @group functional
     */
    public function testLdapAddInvalidEntry()
    {
        $this->setExpectedException(LdapException::class);
        $this->executeSearchQuery(1);

        // The entry is missing a subject name
        $entry = new Entry('cn=Charles Sarrazin,dc=makhan,dc=com', array(
            'objectclass' => array(
                'inetOrgPerson',
            ),
        ));

        $em = $this->adapter->getEntryManager();
        $em->add($entry);
    }

    /**
     * @group functional
     */
    public function testLdapUpdate()
    {
        $result = $this->executeSearchQuery(1);

        $entry = $result[0];
        $this->assertNull($entry->getAttribute('email'));

        $em = $this->adapter->getEntryManager();
        $em->update($entry);

        $result = $this->executeSearchQuery(1);

        $entry = $result[0];
        $this->assertNull($entry->getAttribute('email'));

        $entry->removeAttribute('email');
        $em->update($entry);

        $result = $this->executeSearchQuery(1);
        $entry = $result[0];
        $this->assertNull($entry->getAttribute('email'));
    }

    /**
     * @return Collection|Entry[]
     */
    private function executeSearchQuery($expectedResults = 1)
    {
        $results = $this
            ->adapter
            ->createQuery('dc=makhan,dc=com', '(objectclass=person)')
            ->execute()
        ;

        $this->assertCount($expectedResults, $results);

        return $results;
    }
}
