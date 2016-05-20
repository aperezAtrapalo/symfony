<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Cache\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Makhan\Component\Cache\CacheItem;

/**
 * Interface for adapters managing instances of Makhan's {@see CacheItem}.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface AdapterInterface extends CacheItemPoolInterface
{
}
