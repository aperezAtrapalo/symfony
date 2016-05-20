<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Loader;

use Makhan\Component\Config\Exception\FileLoaderLoadException;

/**
 * DelegatingLoader delegates loading to other loaders using a loader resolver.
 *
 * This loader acts as an array of LoaderInterface objects - each having
 * a chance to load a given resource (handled by the resolver)
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class DelegatingLoader extends Loader
{
    /**
     * Constructor.
     *
     * @param LoaderResolverInterface $resolver A LoaderResolverInterface instance
     */
    public function __construct(LoaderResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        if (false === $loader = $this->resolver->resolve($resource, $type)) {
            throw new FileLoaderLoadException($resource);
        }

        return $loader->load($resource, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return false !== $this->resolver->resolve($resource, $type);
    }
}
