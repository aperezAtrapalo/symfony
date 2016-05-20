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
use Makhan\Component\Translation\Dumper\YamlFileDumper;

class YamlFileDumperTest extends \PHPUnit_Framework_TestCase
{
    public function testTreeFormatCatalogue()
    {
        $catalogue = new MessageCatalogue('en');
        $catalogue->add(
            array(
                'foo.bar1' => 'value1',
                'foo.bar2' => 'value2',
            ));

        $dumper = new YamlFileDumper();

        $this->assertStringEqualsFile(__DIR__.'/../fixtures/messages.yml', $dumper->formatCatalogue($catalogue, 'messages', array('as_tree' => true, 'inline' => 999)));
    }

    public function testLinearFormatCatalogue()
    {
        $catalogue = new MessageCatalogue('en');
        $catalogue->add(
            array(
                'foo.bar1' => 'value1',
                'foo.bar2' => 'value2',
            ));

        $dumper = new YamlFileDumper();

        $this->assertStringEqualsFile(__DIR__.'/../fixtures/messages_linear.yml', $dumper->formatCatalogue($catalogue, 'messages'));
    }
}
