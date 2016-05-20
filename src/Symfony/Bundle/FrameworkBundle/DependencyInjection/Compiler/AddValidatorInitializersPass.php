<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Reference;
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;

class AddValidatorInitializersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('validator.builder')) {
            return;
        }

        $validatorBuilder = $container->getDefinition('validator.builder');

        $initializers = array();
        foreach ($container->findTaggedServiceIds('validator.initializer') as $id => $attributes) {
            $initializers[] = new Reference($id);
        }

        $validatorBuilder->addMethodCall('addObjectInitializers', array($initializers));
    }
}
