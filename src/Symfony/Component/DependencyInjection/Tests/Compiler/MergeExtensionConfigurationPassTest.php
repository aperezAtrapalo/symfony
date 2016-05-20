<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\Tests\Compiler;

use Makhan\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\ParameterBag\ParameterBag;

class MergeExtensionConfigurationPassTest extends \PHPUnit_Framework_TestCase
{
    public function testExpressionLanguageProviderForwarding()
    {
        $tmpProviders = array();

        $extension = $this->getMock('Makhan\\Component\\DependencyInjection\\Extension\\ExtensionInterface');
        $extension->expects($this->any())
            ->method('getXsdValidationBasePath')
            ->will($this->returnValue(false));
        $extension->expects($this->any())
            ->method('getNamespace')
            ->will($this->returnValue('http://example.org/schema/dic/foo'));
        $extension->expects($this->any())
            ->method('getAlias')
            ->will($this->returnValue('foo'));
        $extension->expects($this->once())
            ->method('load')
            ->will($this->returnCallback(function (array $config, ContainerBuilder $container) use (&$tmpProviders) {
                $tmpProviders = $container->getExpressionLanguageProviders();
            }));

        $provider = $this->getMock('Makhan\\Component\\ExpressionLanguage\\ExpressionFunctionProviderInterface');
        $container = new ContainerBuilder(new ParameterBag());
        $container->registerExtension($extension);
        $container->prependExtensionConfig('foo', array('bar' => true));
        $container->addExpressionLanguageProvider($provider);

        $pass = new MergeExtensionConfigurationPass();
        $pass->process($container);

        $this->assertEquals(array($provider), $tmpProviders);
    }
}
