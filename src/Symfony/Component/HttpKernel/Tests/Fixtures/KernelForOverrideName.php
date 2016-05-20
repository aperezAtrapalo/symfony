<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\Fixtures;

use Makhan\Component\HttpKernel\Kernel;
use Makhan\Component\Config\Loader\LoaderInterface;

class KernelForOverrideName extends Kernel
{
    protected $name = 'overridden';

    public function registerBundles()
    {
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}
