<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\Dumper;

use Makhan\Component\DependencyInjection\ContainerBuilder;

/**
 * Dumper is the abstract class for all built-in dumpers.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
abstract class Dumper implements DumperInterface
{
    protected $container;

    /**
     * @param ContainerBuilder $container The service container to dump
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }
}
