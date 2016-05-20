<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Mapping\Loader;

use Makhan\Component\Validator\Mapping\ClassMetadata;
use Makhan\Component\Validator\Mapping\Loader\LoaderInterface;

class FilesLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testCallsGetFileLoaderInstanceForeachPath()
    {
        $loader = $this->getFilesLoader($this->getFileLoader());
        $this->assertEquals(4, $loader->getTimesCalled());
    }

    public function testCallsActualFileLoaderForMetadata()
    {
        $fileLoader = $this->getFileLoader();
        $fileLoader->expects($this->exactly(4))
            ->method('loadClassMetadata');
        $loader = $this->getFilesLoader($fileLoader);
        $loader->loadClassMetadata(new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity'));
    }

    public function getFilesLoader(LoaderInterface $loader)
    {
        return $this->getMockForAbstractClass('Makhan\Component\Validator\Tests\Fixtures\FilesLoader', array(array(
            __DIR__.'/constraint-mapping.xml',
            __DIR__.'/constraint-mapping.yaml',
            __DIR__.'/constraint-mapping.test',
            __DIR__.'/constraint-mapping.txt',
        ), $loader));
    }

    public function getFileLoader()
    {
        return $this->getMock('Makhan\Component\Validator\Mapping\Loader\LoaderInterface');
    }
}
