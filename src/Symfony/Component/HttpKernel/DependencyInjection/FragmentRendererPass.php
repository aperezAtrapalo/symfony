<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\DependencyInjection;

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds services tagged kernel.fragment_renderer as HTTP content rendering strategies.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class FragmentRendererPass implements CompilerPassInterface
{
    private $handlerService;
    private $rendererTag;

    /**
     * @param string $handlerService Service name of the fragment handler in the container
     * @param string $rendererTag    Tag name used for fragments
     */
    public function __construct($handlerService = 'fragment.handler', $rendererTag = 'kernel.fragment_renderer')
    {
        $this->handlerService = $handlerService;
        $this->rendererTag = $rendererTag;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->handlerService)) {
            return;
        }

        $definition = $container->getDefinition($this->handlerService);
        foreach ($container->findTaggedServiceIds($this->rendererTag) as $id => $tags) {
            $def = $container->getDefinition($id);
            if (!$def->isPublic()) {
                throw new \InvalidArgumentException(sprintf('The service "%s" must be public as fragment renderer are lazy-loaded.', $id));
            }

            if ($def->isAbstract()) {
                throw new \InvalidArgumentException(sprintf('The service "%s" must not be abstract as fragment renderer are lazy-loaded.', $id));
            }

            $class = $container->getParameterBag()->resolveValue($def->getClass());
            $interface = 'Makhan\Component\HttpKernel\Fragment\FragmentRendererInterface';
            if (!is_subclass_of($class, $interface)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
            }

            foreach ($tags as $tag) {
                $definition->addMethodCall('addRendererService', array($tag['alias'], $id));
            }
        }
    }
}
