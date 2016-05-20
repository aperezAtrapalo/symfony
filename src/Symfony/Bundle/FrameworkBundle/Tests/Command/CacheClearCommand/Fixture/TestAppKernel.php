<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Command\CacheClearCommand\Fixture;

use Makhan\Bundle\FrameworkBundle\FrameworkBundle;
use Makhan\Component\Config\Loader\LoaderInterface;
use Makhan\Component\HttpKernel\Kernel;

class TestAppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
        );
    }

    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.DIRECTORY_SEPARATOR.'config.yml');
    }
}
