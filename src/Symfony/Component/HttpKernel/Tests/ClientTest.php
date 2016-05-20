<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests;

use Makhan\Component\HttpKernel\Client;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpFoundation\StreamedResponse;
use Makhan\Component\HttpFoundation\Cookie;
use Makhan\Component\HttpFoundation\File\UploadedFile;
use Makhan\Component\HttpKernel\Tests\Fixtures\TestClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testDoRequest()
    {
        $client = new Client(new TestHttpKernel());

        $client->request('GET', '/');
        $this->assertEquals('Request: /', $client->getResponse()->getContent(), '->doRequest() uses the request handler to make the request');
        $this->assertInstanceOf('Makhan\Component\BrowserKit\Request', $client->getInternalRequest());
        $this->assertInstanceOf('Makhan\Component\HttpFoundation\Request', $client->getRequest());
        $this->assertInstanceOf('Makhan\Component\BrowserKit\Response', $client->getInternalResponse());
        $this->assertInstanceOf('Makhan\Component\HttpFoundation\Response', $client->getResponse());

        $client->request('GET', 'http://www.example.com/');
        $this->assertEquals('Request: /', $client->getResponse()->getContent(), '->doRequest() uses the request handler to make the request');
        $this->assertEquals('www.example.com', $client->getRequest()->getHost(), '->doRequest() uses the request handler to make the request');

        $client->request('GET', 'http://www.example.com/?parameter=http://google.com');
        $this->assertEquals('http://www.example.com/?parameter='.urlencode('http://google.com'), $client->getRequest()->getUri(), '->doRequest() uses the request handler to make the request');
    }

    public function testGetScript()
    {
        $client = new TestClient(new TestHttpKernel());
        $client->insulate();
        $client->request('GET', '/');

        $this->assertEquals('Request: /', $client->getResponse()->getContent(), '->getScript() returns a script that uses the request handler to make the request');
    }

    public function testFilterResponseConvertsCookies()
    {
        $client = new Client(new TestHttpKernel());

        $r = new \ReflectionObject($client);
        $m = $r->getMethod('filterResponse');
        $m->setAccessible(true);

        $expected = array(
            'foo=bar; expires=Sun, 15 Feb 2009 20:00:00 GMT; domain=http://example.com; path=/foo; secure; httponly',
            'foo1=bar1; expires=Sun, 15 Feb 2009 20:00:00 GMT; domain=http://example.com; path=/foo; secure; httponly',
        );

        $response = new Response();
        $response->headers->setCookie(new Cookie('foo', 'bar', \DateTime::createFromFormat('j-M-Y H:i:s T', '15-Feb-2009 20:00:00 GMT')->format('U'), '/foo', 'http://example.com', true, true));
        $domResponse = $m->invoke($client, $response);
        $this->assertEquals($expected[0], $domResponse->getHeader('Set-Cookie'));

        $response = new Response();
        $response->headers->setCookie(new Cookie('foo', 'bar', \DateTime::createFromFormat('j-M-Y H:i:s T', '15-Feb-2009 20:00:00 GMT')->format('U'), '/foo', 'http://example.com', true, true));
        $response->headers->setCookie(new Cookie('foo1', 'bar1', \DateTime::createFromFormat('j-M-Y H:i:s T', '15-Feb-2009 20:00:00 GMT')->format('U'), '/foo', 'http://example.com', true, true));
        $domResponse = $m->invoke($client, $response);
        $this->assertEquals($expected[0], $domResponse->getHeader('Set-Cookie'));
        $this->assertEquals($expected, $domResponse->getHeader('Set-Cookie', false));
    }

    public function testFilterResponseSupportsStreamedResponses()
    {
        $client = new Client(new TestHttpKernel());

        $r = new \ReflectionObject($client);
        $m = $r->getMethod('filterResponse');
        $m->setAccessible(true);

        $response = new StreamedResponse(function () {
            echo 'foo';
        });

        $domResponse = $m->invoke($client, $response);
        $this->assertEquals('foo', $domResponse->getContent());
    }

    public function testUploadedFile()
    {
        $source = tempnam(sys_get_temp_dir(), 'source');
        $target = sys_get_temp_dir().'/sf.moved.file';
        @unlink($target);

        $kernel = new TestHttpKernel();
        $client = new Client($kernel);

        $files = array(
            array('tmp_name' => $source, 'name' => 'original', 'type' => 'mime/original', 'size' => 123, 'error' => UPLOAD_ERR_OK),
            new UploadedFile($source, 'original', 'mime/original', 123, UPLOAD_ERR_OK, true),
        );

        $file = null;
        foreach ($files as $file) {
            $client->request('POST', '/', array(), array('foo' => $file));

            $files = $client->getRequest()->files->all();

            $this->assertCount(1, $files);

            $file = $files['foo'];

            $this->assertEquals('original', $file->getClientOriginalName());
            $this->assertEquals('mime/original', $file->getClientMimeType());
            $this->assertEquals('123', $file->getClientSize());
            $this->assertTrue($file->isValid());
        }

        $file->move(dirname($target), basename($target));

        $this->assertFileExists($target);
        unlink($target);
    }

    public function testUploadedFileWhenNoFileSelected()
    {
        $kernel = new TestHttpKernel();
        $client = new Client($kernel);

        $file = array('tmp_name' => '', 'name' => '', 'type' => '', 'size' => 0, 'error' => UPLOAD_ERR_NO_FILE);

        $client->request('POST', '/', array(), array('foo' => $file));

        $files = $client->getRequest()->files->all();

        $this->assertCount(1, $files);
        $this->assertNull($files['foo']);
    }

    public function testUploadedFileWhenSizeExceedsUploadMaxFileSize()
    {
        $source = tempnam(sys_get_temp_dir(), 'source');

        $kernel = new TestHttpKernel();
        $client = new Client($kernel);

        $file = $this
            ->getMockBuilder('Makhan\Component\HttpFoundation\File\UploadedFile')
            ->setConstructorArgs(array($source, 'original', 'mime/original', 123, UPLOAD_ERR_OK, true))
            ->setMethods(array('getSize'))
            ->getMock()
        ;

        $file->expects($this->once())
            ->method('getSize')
            ->will($this->returnValue(INF))
        ;

        $client->request('POST', '/', array(), array($file));

        $files = $client->getRequest()->files->all();

        $this->assertCount(1, $files);

        $file = $files[0];

        $this->assertFalse($file->isValid());
        $this->assertEquals(UPLOAD_ERR_INI_SIZE, $file->getError());
        $this->assertEquals('mime/original', $file->getClientMimeType());
        $this->assertEquals('original', $file->getClientOriginalName());
        $this->assertEquals(0, $file->getClientSize());

        unlink($source);
    }
}
