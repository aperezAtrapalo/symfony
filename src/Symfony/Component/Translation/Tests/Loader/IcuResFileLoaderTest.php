<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Translation\Tests\Loader;

use Makhan\Component\Translation\Loader\IcuResFileLoader;
use Makhan\Component\Config\Resource\DirectoryResource;

/**
 * @requires extension intl
 */
class IcuResFileLoaderTest extends LocalizedTestCase
{
    public function testLoad()
    {
        // resource is build using genrb command
        $loader = new IcuResFileLoader();
        $resource = __DIR__.'/../fixtures/resourcebundle/res';
        $catalogue = $loader->load($resource, 'en', 'domain1');

        $this->assertEquals(array('foo' => 'bar'), $catalogue->all('domain1'));
        $this->assertEquals('en', $catalogue->getLocale());
        $this->assertEquals(array(new DirectoryResource($resource)), $catalogue->getResources());
    }

    /**
     * @expectedException \Makhan\Component\Translation\Exception\NotFoundResourceException
     */
    public function testLoadNonExistingResource()
    {
        $loader = new IcuResFileLoader();
        $loader->load(__DIR__.'/../fixtures/non-existing.txt', 'en', 'domain1');
    }

    /**
     * @expectedException \Makhan\Component\Translation\Exception\InvalidResourceException
     */
    public function testLoadInvalidResource()
    {
        $loader = new IcuResFileLoader();
        $loader->load(__DIR__.'/../fixtures/resourcebundle/corrupted', 'en', 'domain1');
    }
}
