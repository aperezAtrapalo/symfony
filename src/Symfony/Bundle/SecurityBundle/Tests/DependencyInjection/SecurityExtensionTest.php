<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Tests\DependencyInjection;

use Makhan\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Makhan\Bundle\SecurityBundle\SecurityBundle;
use Makhan\Bundle\SecurityBundle\Tests\DependencyInjection\Fixtures\UserProvider\DummyProvider;
use Makhan\Component\DependencyInjection\ContainerBuilder;

class SecurityExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Makhan\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The check_path "/some_area/login_check" for login method "form_login" is not matched by the firewall pattern "/secured_area/.*".
     */
    public function testInvalidCheckPath()
    {
        $container = $this->getRawContainer();

        $container->loadFromExtension('security', array(
            'providers' => array(
                'default' => array('id' => 'foo'),
            ),

            'firewalls' => array(
                'some_firewall' => array(
                    'pattern' => '/secured_area/.*',
                    'form_login' => array(
                        'check_path' => '/some_area/login_check',
                    ),
                ),
            ),
        ));

        $container->compile();
    }

    /**
     * @expectedException \Makhan\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage No authentication listener registered for firewall "some_firewall"
     */
    public function testFirewallWithoutAuthenticationListener()
    {
        $container = $this->getRawContainer();

        $container->loadFromExtension('security', array(
            'providers' => array(
                'default' => array('id' => 'foo'),
            ),

            'firewalls' => array(
                'some_firewall' => array(
                    'pattern' => '/.*',
                ),
            ),
        ));

        $container->compile();
    }

    /**
     * @expectedException \Makhan\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Unable to create definition for "security.user.provider.concrete.my_foo" user provider
     */
    public function testFirewallWithInvalidUserProvider()
    {
        $container = $this->getRawContainer();

        $extension = $container->getExtension('security');
        $extension->addUserProviderFactory(new DummyProvider());

        $container->loadFromExtension('security', array(
            'providers' => array(
                'my_foo' => array('foo' => array()),
            ),

            'firewalls' => array(
                'some_firewall' => array(
                    'pattern' => '/.*',
                    'http_basic' => array(),
                ),
            ),
        ));

        $container->compile();
    }

    public function testDisableRoleHierarchyVoter()
    {
        $container = $this->getRawContainer();

        $container->loadFromExtension('security', array(
            'providers' => array(
                'default' => array('id' => 'foo'),
            ),

            'role_hierarchy' => null,

            'firewalls' => array(
                'some_firewall' => array(
                    'pattern' => '/.*',
                    'http_basic' => null,
                ),
            ),
        ));

        $container->compile();

        $this->assertFalse($container->hasDefinition('security.access.role_hierarchy_voter'));
    }

    protected function getRawContainer()
    {
        $container = new ContainerBuilder();
        $security = new SecurityExtension();
        $container->registerExtension($security);

        $bundle = new SecurityBundle();
        $bundle->build($container);

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());

        return $container;
    }

    protected function getContainer()
    {
        $container = $this->getRawContainer();
        $container->compile();

        return $container;
    }
}
