<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Cache\Tests\Adapter;

use Makhan\Component\Cache\Adapter\ArrayAdapter;
use Makhan\Component\Cache\Adapter\ProxyAdapter;

/**
 * @group time-sensitive
 */
class NamespacedProxyAdapterTest extends ProxyAdapterTest
{
    public function createCachePool()
    {
        return new ProxyAdapter(new ArrayAdapter(), 'foo');
    }
}
