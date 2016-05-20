<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\LazyProxy\Instantiator;

use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\DependencyInjection\Definition;

/**
 * {@inheritdoc}
 *
 * Noop proxy instantiator - simply produces the real service instead of a proxy instance.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class RealServiceInstantiator implements InstantiatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function instantiateProxy(ContainerInterface $container, Definition $definition, $id, $realInstantiator)
    {
        return call_user_func($realInstantiator);
    }
}
