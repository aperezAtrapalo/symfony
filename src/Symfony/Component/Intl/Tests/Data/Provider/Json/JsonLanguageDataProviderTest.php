<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Tests\Data\Provider\Json;

use Makhan\Component\Intl\Data\Bundle\Reader\BundleReaderInterface;
use Makhan\Component\Intl\Data\Bundle\Reader\JsonBundleReader;
use Makhan\Component\Intl\Intl;
use Makhan\Component\Intl\Tests\Data\Provider\AbstractLanguageDataProviderTest;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @group intl-data
 */
class JsonLanguageDataProviderTest extends AbstractLanguageDataProviderTest
{
    protected function getDataDirectory()
    {
        return Intl::getDataDirectory();
    }

    /**
     * @return BundleReaderInterface
     */
    protected function createBundleReader()
    {
        return new JsonBundleReader();
    }
}
