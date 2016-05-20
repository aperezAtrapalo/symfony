<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Tests\ExpressionLanguage;

use Makhan\Bridge\Doctrine\ExpressionLanguage\DoctrineParserCache;

class DoctrineParserCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testFetch()
    {
        $doctrineCacheMock = $this->getMock('Doctrine\Common\Cache\Cache');
        $parserCache = new DoctrineParserCache($doctrineCacheMock);

        $doctrineCacheMock->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue('bar'));

        $result = $parserCache->fetch('foo');

        $this->assertEquals('bar', $result);
    }

    public function testFetchUnexisting()
    {
        $doctrineCacheMock = $this->getMock('Doctrine\Common\Cache\Cache');
        $parserCache = new DoctrineParserCache($doctrineCacheMock);

        $doctrineCacheMock
            ->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue(false));

        $this->assertNull($parserCache->fetch(''));
    }

    public function testSave()
    {
        $doctrineCacheMock = $this->getMock('Doctrine\Common\Cache\Cache');
        $parserCache = new DoctrineParserCache($doctrineCacheMock);

        $expression = $this->getMockBuilder('Makhan\Component\ExpressionLanguage\ParsedExpression')
            ->disableOriginalConstructor()
            ->getMock();

        $doctrineCacheMock->expects($this->once())
            ->method('save')
            ->with('foo', $expression);

        $parserCache->save('foo', $expression);
    }
}
