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

use Makhan\Component\Console\Descriptor\MarkdownDescriptor;

class MarkdownDescriptorTest extends AbstractDescriptorTest
{
    protected function getDescriptor()
    {
        return new MarkdownDescriptor();
    }

    protected function getFormat()
    {
        return 'md';
    }
}
