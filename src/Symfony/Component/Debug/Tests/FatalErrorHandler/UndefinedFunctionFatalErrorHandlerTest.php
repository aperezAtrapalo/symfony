<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Debug\Tests\FatalErrorHandler;

use Makhan\Component\Debug\Exception\FatalErrorException;
use Makhan\Component\Debug\FatalErrorHandler\UndefinedFunctionFatalErrorHandler;

class UndefinedFunctionFatalErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideUndefinedFunctionData
     */
    public function testUndefinedFunction($error, $translatedMessage)
    {
        $handler = new UndefinedFunctionFatalErrorHandler();
        $exception = $handler->handleError($error, new FatalErrorException('', 0, $error['type'], $error['file'], $error['line']));

        $this->assertInstanceOf('Makhan\Component\Debug\Exception\UndefinedFunctionException', $exception);
        // class names are case insensitive and PHP/HHVM do not return the same
        $this->assertSame(strtolower($translatedMessage), strtolower($exception->getMessage()));
        $this->assertSame($error['type'], $exception->getSeverity());
        $this->assertSame($error['file'], $exception->getFile());
        $this->assertSame($error['line'], $exception->getLine());
    }

    public function provideUndefinedFunctionData()
    {
        return array(
            array(
                array(
                    'type' => 1,
                    'line' => 12,
                    'file' => 'foo.php',
                    'message' => 'Call to undefined function test_namespaced_function()',
                ),
                "Attempted to call function \"test_namespaced_function\" from the global namespace.\nDid you mean to call \"\\makhan\\component\\debug\\tests\\fatalerrorhandler\\test_namespaced_function\"?",
            ),
            array(
                array(
                    'type' => 1,
                    'line' => 12,
                    'file' => 'foo.php',
                    'message' => 'Call to undefined function Foo\\Bar\\Baz\\test_namespaced_function()',
                ),
                "Attempted to call function \"test_namespaced_function\" from namespace \"Foo\\Bar\\Baz\".\nDid you mean to call \"\\makhan\\component\\debug\\tests\\fatalerrorhandler\\test_namespaced_function\"?",
            ),
            array(
                array(
                    'type' => 1,
                    'line' => 12,
                    'file' => 'foo.php',
                    'message' => 'Call to undefined function foo()',
                ),
                'Attempted to call function "foo" from the global namespace.',
            ),
            array(
                array(
                    'type' => 1,
                    'line' => 12,
                    'file' => 'foo.php',
                    'message' => 'Call to undefined function Foo\\Bar\\Baz\\foo()',
                ),
                'Attempted to call function "foo" from namespace "Foo\Bar\Baz".',
            ),
        );
    }
}

function test_namespaced_function()
{
}
