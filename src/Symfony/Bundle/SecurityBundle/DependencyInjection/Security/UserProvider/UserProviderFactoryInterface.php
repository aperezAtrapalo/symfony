<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider;

use Makhan\Component\Config\Definition\Builder\NodeDefinition;
use Makhan\Component\DependencyInjection\ContainerBuilder;

/**
 * UserProviderFactoryInterface is the interface for all user provider factories.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
interface UserProviderFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config);

    public function getKey();

    public function addConfiguration(NodeDefinition $builder);
}
