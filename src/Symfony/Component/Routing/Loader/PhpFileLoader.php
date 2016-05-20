<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Routing\Loader;

use Makhan\Component\Config\Loader\FileLoader;
use Makhan\Component\Config\Resource\FileResource;
use Makhan\Component\Routing\RouteCollection;

/**
 * PhpFileLoader loads routes from a PHP file.
 *
 * The file must return a RouteCollection instance.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class PhpFileLoader extends FileLoader
{
    /**
     * Loads a PHP file.
     *
     * @param string      $file A PHP file path
     * @param string|null $type The resource type
     *
     * @return RouteCollection A RouteCollection instance
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);
        $this->setCurrentDir(dirname($path));

        $collection = self::includeFile($path, $this);
        $collection->addResource(new FileResource($path));

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'php' === $type);
    }

    /**
     * Safe include. Used for scope isolation.
     *
     * @param string        $file   File to include
     * @param PhpFileLoader $loader the loader variable is exposed to the included file below
     *
     * @return RouteCollection
     */
    private static function includeFile($file, PhpFileLoader $loader)
    {
        return include $file;
    }
}
