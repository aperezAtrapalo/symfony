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
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Makhan\Component\DependencyInjection\Reference;

/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
class LoggingTranslatorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasAlias('logger') || !$container->hasAlias('translator')) {
            return;
        }

        // skip if the makhan/translation version is lower than 2.6
        if (!interface_exists('Makhan\Component\Translation\TranslatorBagInterface')) {
            return;
        }

        if ($container->hasParameter('translator.logging') && $container->getParameter('translator.logging')) {
            $translatorAlias = $container->getAlias('translator');
            $definition = $container->getDefinition((string) $translatorAlias);
            $class = $container->getParameterBag()->resolveValue($definition->getClass());

            if (is_subclass_of($class, 'Makhan\Component\Translation\TranslatorInterface') && is_subclass_of($class, 'Makhan\Component\Translation\TranslatorBagInterface')) {
                $container->getDefinition('translator.logging')->setDecoratedService('translator');
                $container->getDefinition('translation.warmer')->replaceArgument(0, new Reference('translator.logging.inner'));
            }
        }
    }
}
