<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Yaml\Tests;

use Makhan\Component\Yaml\Exception\ParseException;

class ParseExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMessage()
    {
        $exception = new ParseException('Error message', 42, 'foo: bar', '/var/www/app/config.yml');
        $message = 'Error message in "/var/www/app/config.yml" at line 42 (near "foo: bar")';

        $this->assertEquals($message, $exception->getMessage());
    }

    public function testGetMessageWithUnicodeInFilename()
    {
        $exception = new ParseException('Error message', 42, 'foo: bar', 'äöü.yml');
        $message = 'Error message in "äöü.yml" at line 42 (near "foo: bar")';

        $this->assertEquals($message, $exception->getMessage());
    }
}
