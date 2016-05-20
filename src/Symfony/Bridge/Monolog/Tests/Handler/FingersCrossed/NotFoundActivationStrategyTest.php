<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Monolog\Tests\Handler\FingersCrossed;

use Makhan\Bridge\Monolog\Handler\FingersCrossed\NotFoundActivationStrategy;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\RequestStack;
use Makhan\Component\HttpKernel\Exception\HttpException;
use Monolog\Logger;

class NotFoundActivationStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider isActivatedProvider
     */
    public function testIsActivated($url, $record, $expected)
    {
        $requestStack = new RequestStack();
        $requestStack->push(Request::create($url));

        $strategy = new NotFoundActivationStrategy($requestStack, array('^/foo', 'bar'), Logger::WARNING);

        $this->assertEquals($expected, $strategy->isHandlerActivated($record));
    }

    public function isActivatedProvider()
    {
        return array(
            array('/test',      array('level' => Logger::DEBUG), false),
            array('/foo',       array('level' => Logger::DEBUG, 'context' => $this->getContextException(404)), false),
            array('/baz/bar',   array('level' => Logger::ERROR, 'context' => $this->getContextException(404)), false),
            array('/foo',       array('level' => Logger::ERROR, 'context' => $this->getContextException(404)), false),
            array('/foo',       array('level' => Logger::ERROR, 'context' => $this->getContextException(500)), true),

            array('/test',      array('level' => Logger::ERROR), true),
            array('/baz',       array('level' => Logger::ERROR, 'context' => $this->getContextException(404)), true),
            array('/baz',       array('level' => Logger::ERROR, 'context' => $this->getContextException(500)), true),
        );
    }

    protected function getContextException($code)
    {
        return array('exception' => new HttpException($code));
    }
}
