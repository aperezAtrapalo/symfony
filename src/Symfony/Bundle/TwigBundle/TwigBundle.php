<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle;

use Makhan\Component\HttpKernel\Bundle\Bundle;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Bundle\TwigBundle\DependencyInjection\Compiler\TwigEnvironmentPass;
use Makhan\Bundle\TwigBundle\DependencyInjection\Compiler\TwigLoaderPass;
use Makhan\Bundle\TwigBundle\DependencyInjection\Compiler\ExceptionListenerPass;
use Makhan\Bundle\TwigBundle\DependencyInjection\Compiler\ExtensionPass;

/**
 * Bundle.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class TwigBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ExtensionPass());
        $container->addCompilerPass(new TwigEnvironmentPass());
        $container->addCompilerPass(new TwigLoaderPass());
        $container->addCompilerPass(new ExceptionListenerPass());
    }
}
