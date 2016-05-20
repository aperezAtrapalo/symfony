<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Translation\Tests\Dumper;

use Makhan\Component\Translation\MessageCatalogue;
use Makhan\Component\Translation\Dumper\IniFileDumper;

class IniFileDumperTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatCatalogue()
    {
        $catalogue = new MessageCatalogue('en');
        $catalogue->add(array('foo' => 'bar'));

        $dumper = new IniFileDumper();

        $this->assertStringEqualsFile(__DIR__.'/../fixtures/resources.ini', $dumper->formatCatalogue($catalogue, 'messages'));
    }
}
