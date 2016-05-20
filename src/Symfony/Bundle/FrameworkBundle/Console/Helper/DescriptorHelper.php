<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Console\Helper;

use Makhan\Bundle\FrameworkBundle\Console\Descriptor\JsonDescriptor;
use Makhan\Bundle\FrameworkBundle\Console\Descriptor\MarkdownDescriptor;
use Makhan\Bundle\FrameworkBundle\Console\Descriptor\TextDescriptor;
use Makhan\Bundle\FrameworkBundle\Console\Descriptor\XmlDescriptor;
use Makhan\Component\Console\Helper\DescriptorHelper as BaseDescriptorHelper;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class DescriptorHelper extends BaseDescriptorHelper
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this
            ->register('txt', new TextDescriptor())
            ->register('xml', new XmlDescriptor())
            ->register('json', new JsonDescriptor())
            ->register('md', new MarkdownDescriptor())
        ;
    }
}
