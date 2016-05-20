<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Translation\Tests\Catalogue;

use Makhan\Component\Translation\MessageCatalogue;
use Makhan\Component\Translation\MessageCatalogueInterface;

abstract class AbstractOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEmptyDomains()
    {
        $this->assertEquals(
            array(),
            $this->createOperation(
                new MessageCatalogue('en'),
                new MessageCatalogue('en')
            )->getDomains()
        );
    }

    public function testGetMergedDomains()
    {
        $this->assertEquals(
            array('a', 'b', 'c'),
            $this->createOperation(
                new MessageCatalogue('en', array('a' => array(), 'b' => array())),
                new MessageCatalogue('en', array('b' => array(), 'c' => array()))
            )->getDomains()
        );
    }

    public function testGetMessagesFromUnknownDomain()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->createOperation(
            new MessageCatalogue('en'),
            new MessageCatalogue('en')
        )->getMessages('domain');
    }

    public function testGetEmptyMessages()
    {
        $this->assertEquals(
            array(),
            $this->createOperation(
                new MessageCatalogue('en', array('a' => array())),
                new MessageCatalogue('en')
            )->getMessages('a')
        );
    }

    public function testGetEmptyResult()
    {
        $this->assertEquals(
            new MessageCatalogue('en'),
            $this->createOperation(
                new MessageCatalogue('en'),
                new MessageCatalogue('en')
            )->getResult()
        );
    }

    abstract protected function createOperation(MessageCatalogueInterface $source, MessageCatalogueInterface $target);
}
