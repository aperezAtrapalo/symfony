<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console\Tests\Descriptor;

use Makhan\Component\Console\Descriptor\JsonDescriptor;
use Makhan\Component\Console\Output\BufferedOutput;

class JsonDescriptorTest extends AbstractDescriptorTest
{
    protected function getDescriptor()
    {
        return new JsonDescriptor();
    }

    protected function getFormat()
    {
        return 'json';
    }

    protected function assertDescription($expectedDescription, $describedObject)
    {
        $output = new BufferedOutput(BufferedOutput::VERBOSITY_NORMAL, true);
        $this->getDescriptor()->describe($output, $describedObject, array('raw_output' => true));
        $this->assertEquals(json_decode(trim($expectedDescription), true), json_decode(trim(str_replace(PHP_EOL, "\n", $output->fetch())), true));
    }
}
