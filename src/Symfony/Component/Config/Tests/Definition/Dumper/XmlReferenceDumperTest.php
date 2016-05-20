<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Tests\Definition\Dumper;

use Makhan\Component\Config\Definition\Dumper\XmlReferenceDumper;
use Makhan\Component\Config\Tests\Fixtures\Configuration\ExampleConfiguration;

class XmlReferenceDumperTest extends \PHPUnit_Framework_TestCase
{
    public function testDumper()
    {
        $configuration = new ExampleConfiguration();

        $dumper = new XmlReferenceDumper();
        $this->assertEquals($this->getConfigurationAsString(), $dumper->dump($configuration));
    }

    public function testNamespaceDumper()
    {
        $configuration = new ExampleConfiguration();

        $dumper = new XmlReferenceDumper();
        $this->assertEquals(str_replace('http://example.org/schema/dic/acme_root', 'http://makhan.com/schema/dic/makhan', $this->getConfigurationAsString()), $dumper->dump($configuration, 'http://makhan.com/schema/dic/makhan'));
    }

    private function getConfigurationAsString()
    {
        return str_replace("\n", PHP_EOL, <<<EOL
<!-- Namespace: http://example.org/schema/dic/acme_root -->
<!-- scalar-required: Required -->
<!-- enum-with-default: One of "this"; "that" -->
<!-- enum: One of "this"; "that" -->
<config
    boolean="true"
    scalar-empty=""
    scalar-null="null"
    scalar-true="true"
    scalar-false="false"
    scalar-default="default"
    scalar-array-empty=""
    scalar-array-defaults="elem1,elem2"
    scalar-required=""
    enum-with-default="this"
    enum=""
>

    <!-- some info -->
    <!--
        child3: this is a long
                multi-line info text
                which should be indented;
                Example: example setting
    -->
    <array
        child1=""
        child2=""
        child3=""
    />

    <!-- prototype: Parameter name -->
    <parameter name="parameter name">scalar value</parameter>

    <!-- prototype -->
    <connection
        user=""
        pass=""
    />

</config>

EOL
        );
    }
}
