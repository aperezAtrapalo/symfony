<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle;

use Makhan\Component\HttpKernel\Bundle\Bundle;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\DependencyInjection\Config\CustomConfig;

class TestBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var $extension DependencyInjection\TestExtension */
        $extension = $container->getExtension('test');

        $extension->setCustomConfig(new CustomConfig());
    }
}
