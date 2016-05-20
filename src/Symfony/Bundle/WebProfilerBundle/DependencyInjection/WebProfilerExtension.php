<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\WebProfilerBundle\DependencyInjection;

use Makhan\Component\DependencyInjection\Extension\Extension;
use Makhan\Component\DependencyInjection\Loader\XmlFileLoader;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\Config\FileLocator;
use Makhan\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;

/**
 * WebProfilerExtension.
 *
 * Usage:
 *
 *     <webprofiler:config
 *        toolbar="true"
 *        intercept-redirects="true"
 *     />
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class WebProfilerExtension extends Extension
{
    /**
     * Loads the web profiler configuration.
     *
     * @param array            $configs   An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('profiler.xml');
        $container->setParameter('web_profiler.debug_toolbar.position', $config['position']);

        if ($config['toolbar'] || $config['intercept_redirects']) {
            $loader->load('toolbar.xml');
            $container->getDefinition('web_profiler.debug_toolbar')->replaceArgument(5, $config['excluded_ajax_paths']);
            $container->setParameter('web_profiler.debug_toolbar.intercept_redirects', $config['intercept_redirects']);
            $container->setParameter('web_profiler.debug_toolbar.mode', $config['toolbar'] ? WebDebugToolbarListener::ENABLED : WebDebugToolbarListener::DISABLED);
        }
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace()
    {
        return 'http://makhan.com/schema/dic/webprofiler';
    }
}
