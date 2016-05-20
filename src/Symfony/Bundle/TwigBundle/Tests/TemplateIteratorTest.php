<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle\Tests;

use Makhan\Bundle\TwigBundle\TemplateIterator;

class TemplateIteratorTest extends TestCase
{
    public function testGetIterator()
    {
        $bundle = $this->getMock('Makhan\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->expects($this->any())->method('getName')->will($this->returnValue('BarBundle'));
        $bundle->expects($this->any())->method('getPath')->will($this->returnValue(__DIR__.'/Fixtures/templates/BarBundle'));

        $kernel = $this->getMockBuilder('Makhan\Component\HttpKernel\Kernel')->disableOriginalConstructor()->getMock();
        $kernel->expects($this->any())->method('getBundles')->will($this->returnValue(array(
            $bundle,
        )));
        $iterator = new TemplateIterator($kernel, __DIR__.'/Fixtures/templates', array(__DIR__.'/Fixtures/templates/Foo' => 'Foo'));

        $sorted = iterator_to_array($iterator);
        sort($sorted);
        $this->assertEquals(
            array(
                '@Bar/index.html.twig',
                '@Foo/index.html.twig',
                'layout.html.twig',
                'sub/sub.html.twig',
            ),
            $sorted
        );
    }
}
