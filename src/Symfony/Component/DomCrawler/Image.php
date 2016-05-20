<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DomCrawler;

/**
 * Image represents an HTML image (an HTML img tag).
 */
class Image extends AbstractUriElement
{
    public function __construct(\DOMElement $node, $currentUri)
    {
        parent::__construct($node, $currentUri, 'GET');
    }

    protected function getRawUri()
    {
        return $this->node->getAttribute('src');
    }

    protected function setNode(\DOMElement $node)
    {
        if ('img' !== $node->nodeName) {
            throw new \LogicException(sprintf('Unable to visualize a "%s" tag.', $node->nodeName));
        }

        $this->node = $node;
    }
}
