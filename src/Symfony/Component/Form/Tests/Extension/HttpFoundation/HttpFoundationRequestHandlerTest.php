<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\HttpFoundation;

use Makhan\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Makhan\Component\Form\Tests\AbstractRequestHandlerTest;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class HttpFoundationRequestHandlerTest extends AbstractRequestHandlerTest
{
    /**
     * @expectedException \Makhan\Component\Form\Exception\UnexpectedTypeException
     */
    public function testRequestShouldNotBeNull()
    {
        $this->requestHandler->handleRequest($this->getMockForm('name', 'GET'));
    }

    /**
     * @expectedException \Makhan\Component\Form\Exception\UnexpectedTypeException
     */
    public function testRequestShouldBeInstanceOfRequest()
    {
        $this->requestHandler->handleRequest($this->getMockForm('name', 'GET'), new \stdClass());
    }

    protected function setRequestData($method, $data, $files = array())
    {
        $this->request = Request::create('http://localhost', $method, $data, array(), $files);
    }

    protected function getRequestHandler()
    {
        return new HttpFoundationRequestHandler($this->serverParams);
    }

    protected function getMockFile($suffix = '')
    {
        return new UploadedFile(__DIR__.'/../../Fixtures/foo'.$suffix, 'foo'.$suffix);
    }
}
