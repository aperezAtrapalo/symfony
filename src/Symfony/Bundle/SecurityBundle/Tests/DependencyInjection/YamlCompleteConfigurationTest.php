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
use Makhan\Component\DependencyInjection\Loader\YamlFileLoader;
use Makhan\Component\Config\FileLocator;

class YamlCompleteConfigurationTest extends CompleteConfigurationTest
{
    protected function loadFromFile(ContainerBuilder $container, $file)
    {
        $loadXml = new YamlFileLoader($container, new FileLocator(__DIR__.'/Fixtures/yml'));
        $loadXml->load($file.'.yml');
    }
}
