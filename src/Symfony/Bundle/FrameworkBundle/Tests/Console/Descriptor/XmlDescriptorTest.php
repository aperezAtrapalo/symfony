<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Console\Descriptor;

use Makhan\Bundle\FrameworkBundle\Console\Descriptor\XmlDescriptor;

class XmlDescriptorTest extends AbstractDescriptorTest
{
    protected function getDescriptor()
    {
        return new XmlDescriptor();
    }

    protected function getFormat()
    {
        return 'xml';
    }
}
