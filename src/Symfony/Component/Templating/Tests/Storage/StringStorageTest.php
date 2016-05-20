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

use Makhan\Component\Templating\Storage\StringStorage;

class StringStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetContent()
    {
        $storage = new StringStorage('foo');
        $this->assertInstanceOf('Makhan\Component\Templating\Storage\Storage', $storage, 'StringStorage is an instance of Storage');
        $storage = new StringStorage('foo');
        $this->assertEquals('foo', $storage->getContent(), '->getContent() returns the content of the template');
    }
}
