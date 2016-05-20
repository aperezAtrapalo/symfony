<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\DependencyInjection;

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Loader\PhpFileLoader;
use Makhan\Component\Config\FileLocator;

class PhpFrameworkExtensionTest extends FrameworkExtensionTest
{
    protected function loadFromFile(ContainerBuilder $container, $file)
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/Fixtures/php'));
        $loader->load($file.'.php');
    }

    /**
     * @expectedException \LogicException
     */
    public function testAssetsCannotHavePathAndUrl()
    {
        $this->createContainerFromClosure(function ($container) {
            $container->loadFromExtension('framework', array(
                'assets' => array(
                    'base_urls' => 'http://cdn.example.com',
                    'base_path' => '/foo',
                ),
            ));
        });
    }

    /**
     * @expectedException \LogicException
     */
    public function testAssetPackageCannotHavePathAndUrl()
    {
        $this->createContainerFromClosure(function ($container) {
            $container->loadFromExtension('framework', array(
                'assets' => array(
                    'packages' => array(
                        'impossible' => array(
                            'base_urls' => 'http://cdn.example.com',
                            'base_path' => '/foo',
                        ),
                    ),
                ),
            ));
        });
    }
}
