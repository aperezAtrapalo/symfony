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

use Cache\IntegrationTests\CachePoolTest;
use Makhan\Component\Cache\Adapter\FilesystemAdapter;
use Makhan\Component\Cache\Adapter\ArrayAdapter;
use Makhan\Component\Cache\Adapter\ChainAdapter;
use Makhan\Component\Cache\Tests\Fixtures\ExternalAdapter;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class ChainAdapterTest extends CachePoolTest
{
    public function createCachePool()
    {
        if (defined('HHVM_VERSION')) {
            $this->skippedTests['testDeferredSaveWithoutCommit'] = 'Fails on HHVM';
        }

        return new ChainAdapter(array(new ArrayAdapter(), new ExternalAdapter(), new FilesystemAdapter()));
    }

    /**
     * @expectedException \Makhan\Component\Cache\Exception\InvalidArgumentException
     * @expectedExceptionMessage At least one adapter must be specified.
     */
    public function testEmptyAdaptersException()
    {
        new ChainAdapter(array());
    }

    /**
     * @expectedException \Makhan\Component\Cache\Exception\InvalidArgumentException
     * @expectedExceptionMessage The class "stdClass" does not implement
     */
    public function testInvalidAdapterException()
    {
        new ChainAdapter(array(new \stdClass()));
    }
}
