<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Tests;

use Makhan\Component\Config\ConfigCacheFactory;

class ConfigCacheFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid type for callback argument. Expected callable, but got "object".
     */
    public function testCachWithInvalidCallback()
    {
        $cacheFactory = new ConfigCacheFactory(true);

        $cacheFactory->cache('file', new \stdClass());
    }
}
