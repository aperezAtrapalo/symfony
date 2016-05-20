<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\CacheWarmer;

use Makhan\Component\ClassLoader\ClassCollectionLoader;
use Makhan\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Generates the Class Cache (classes.php) file.
 *
 * @author Tugdual Saunier <tucksaun@gmail.com>
 */
class ClassCacheCacheWarmer implements CacheWarmerInterface
{
    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        $classmap = $cacheDir.'/classes.map';

        if (!is_file($classmap)) {
            return;
        }

        if (file_exists($cacheDir.'/classes.php')) {
            return;
        }

        ClassCollectionLoader::load(include($classmap), $cacheDir, 'classes', false);
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * @return bool always true
     */
    public function isOptional()
    {
        return true;
    }
}
