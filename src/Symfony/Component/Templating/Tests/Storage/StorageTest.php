<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Templating\Tests\Storage;

use Makhan\Component\Templating\Storage\Storage;

class StorageTest extends \PHPUnit_Framework_TestCase
{
    public function testMagicToString()
    {
        $storage = new TestStorage('foo');
        $this->assertEquals('foo', (string) $storage, '__toString() returns the template name');
    }
}

class TestStorage extends Storage
{
    public function getContent()
    {
    }
}
