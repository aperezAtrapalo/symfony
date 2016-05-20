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

use Makhan\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Makhan\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Makhan\Component\Routing\RouterInterface;

/**
 * Generates the router matcher and generator classes.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class RouterCacheWarmer implements CacheWarmerInterface
{
    protected $router;

    /**
     * Constructor.
     *
     * @param RouterInterface $router A Router instance
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        if ($this->router instanceof WarmableInterface) {
            $this->router->warmUp($cacheDir);
        }
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
