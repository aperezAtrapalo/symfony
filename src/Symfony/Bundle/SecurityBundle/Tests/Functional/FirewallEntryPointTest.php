<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Tests\Functional;

use Makhan\Bundle\SecurityBundle\Tests\Functional\Bundle\FirewallEntryPointBundle\Security\EntryPointStub;

class FirewallEntryPointTest extends WebTestCase
{
    public function testItUsesTheConfiguredEntryPointWhenUsingUnknownCredentials()
    {
        $client = $this->createClient(array('test_case' => 'FirewallEntryPoint'));

        $client->request('GET', '/secure/resource', array(), array(), array(
            'PHP_AUTH_USER' => 'unknown',
            'PHP_AUTH_PW' => 'credentials',
        ));

        $this->assertEquals(
            EntryPointStub::RESPONSE_TEXT,
            $client->getResponse()->getContent(),
            "Custom entry point wasn't started"
        );
    }

    public function testItUsesTheConfiguredEntryPointFromTheExceptionListenerWithFormLoginAndNoCredentials()
    {
        $client = $this->createClient(array('test_case' => 'FirewallEntryPoint', 'root_config' => 'config_form_login.yml'));

        $client->request('GET', '/secure/resource');

        $this->assertEquals(
            EntryPointStub::RESPONSE_TEXT,
            $client->getResponse()->getContent(),
            "Custom entry point wasn't started"
        );
    }

    public static function setUpBeforeClass()
    {
        parent::deleteTmpDir('FirewallEntryPoint');
    }

    public static function tearDownAfterClass()
    {
        parent::deleteTmpDir('FirewallEntryPoint');
    }
}
