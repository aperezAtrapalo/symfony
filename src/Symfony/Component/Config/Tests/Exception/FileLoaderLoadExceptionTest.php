<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Tests\Exception;

use Makhan\Component\Config\Exception\FileLoaderLoadException;

class FileLoaderLoadExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testMessageCannotLoadResource()
    {
        $exception = new FileLoaderLoadException('resource', null);
        $this->assertEquals('Cannot load resource "resource".', $exception->getMessage());
    }

    public function testMessageCannotImportResourceFromSource()
    {
        $exception = new FileLoaderLoadException('resource', 'sourceResource');
        $this->assertEquals('Cannot import resource "resource" from "sourceResource".', $exception->getMessage());
    }

    public function testMessageCannotImportBundleResource()
    {
        $exception = new FileLoaderLoadException('@resource', 'sourceResource');
        $this->assertEquals(
            'Cannot import resource "@resource" from "sourceResource". '.
            'Make sure the "resource" bundle is correctly registered and loaded in the application kernel class. '.
            'If the bundle is registered, make sure the bundle path "@resource" is not empty.',
            $exception->getMessage()
        );
    }

    public function testMessageHasPreviousErrorWithDotAndUnableToLoad()
    {
        $exception = new FileLoaderLoadException(
            'resource',
            null,
            null,
            new \Exception('There was a previous error with an ending dot.')
        );
        $this->assertEquals(
            'There was a previous error with an ending dot in resource (which is loaded in resource "resource").',
            $exception->getMessage()
        );
    }

    public function testMessageHasPreviousErrorWithoutDotAndUnableToLoad()
    {
        $exception = new FileLoaderLoadException(
            'resource',
            null,
            null,
            new \Exception('There was a previous error with no ending dot')
        );
        $this->assertEquals(
            'There was a previous error with no ending dot in resource (which is loaded in resource "resource").',
            $exception->getMessage()
        );
    }

    public function testMessageHasPreviousErrorAndUnableToLoadBundle()
    {
        $exception = new FileLoaderLoadException(
            '@resource',
            null,
            null,
            new \Exception('There was a previous error with an ending dot.')
        );
        $this->assertEquals(
            'There was a previous error with an ending dot in @resource '.
            '(which is loaded in resource "@resource"). '.
            'Make sure the "resource" bundle is correctly registered and loaded in the application kernel class. '.
            'If the bundle is registered, make sure the bundle path "@resource" is not empty.',
            $exception->getMessage()
        );
    }
}
