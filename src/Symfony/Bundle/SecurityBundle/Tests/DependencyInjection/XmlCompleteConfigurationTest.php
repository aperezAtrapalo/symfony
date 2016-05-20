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

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Loader\XmlFileLoader;
use Makhan\Component\Config\FileLocator;

class XmlCompleteConfigurationTest extends CompleteConfigurationTest
{
    protected function loadFromFile(ContainerBuilder $container, $file)
    {
        $loadXml = new XmlFileLoader($container, new FileLocator(__DIR__.'/Fixtures/xml'));
        $loadXml->load($file.'.xml');
    }
}
