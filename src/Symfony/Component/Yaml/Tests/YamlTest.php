<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Yaml\Tests;

use Makhan\Component\Yaml\Yaml;

class YamlTest extends \PHPUnit_Framework_TestCase
{
    public function testParseAndDump()
    {
        $data = array('lorem' => 'ipsum', 'dolor' => 'sit');
        $yml = Yaml::dump($data);
        $parsed = Yaml::parse($yml);
        $this->assertEquals($data, $parsed);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The indentation must be greater than zero
     */
    public function testZeroIndentationThrowsException()
    {
        Yaml::dump(array('lorem' => 'ipsum', 'dolor' => 'sit'), 2, 0);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The indentation must be greater than zero
     */
    public function testNegativeIndentationThrowsException()
    {
        Yaml::dump(array('lorem' => 'ipsum', 'dolor' => 'sit'), 2, -4);
    }
}
