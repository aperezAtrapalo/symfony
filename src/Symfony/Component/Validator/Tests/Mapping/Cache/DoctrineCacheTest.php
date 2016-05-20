<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Mapping\Cache;

use Doctrine\Common\Cache\ArrayCache;
use Makhan\Component\Validator\Mapping\Cache\DoctrineCache;

class DoctrineCacheTest extends AbstractCacheTest
{
    protected function setUp()
    {
        $this->cache = new DoctrineCache(new ArrayCache());
    }
}
