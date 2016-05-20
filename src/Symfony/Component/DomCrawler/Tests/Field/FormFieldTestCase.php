<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DomCrawler\Tests\Field;

class FormFieldTestCase extends \PHPUnit_Framework_TestCase
{
    protected function createNode($tag, $value, $attributes = array())
    {
        $document = new \DOMDocument();
        $node = $document->createElement($tag, $value);

        foreach ($attributes as $name => $value) {
            $node->setAttribute($name, $value);
        }

        return $node;
    }
}
