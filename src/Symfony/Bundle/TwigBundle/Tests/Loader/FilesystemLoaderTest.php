<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle\Tests\Loader;

use Makhan\Bundle\FrameworkBundle\Templating\TemplateReference;
use Makhan\Bundle\TwigBundle\Loader\FilesystemLoader;
use Makhan\Bundle\TwigBundle\Tests\TestCase;

class FilesystemLoaderTest extends TestCase
{
    public function testGetSource()
    {
        $parser = $this->getMock('Makhan\Component\Templating\TemplateNameParserInterface');
        $locator = $this->getMock('Makhan\Component\Config\FileLocatorInterface');
        $locator
            ->expects($this->once())
            ->method('locate')
            ->will($this->returnValue(__DIR__.'/../DependencyInjection/Fixtures/Resources/views/layout.html.twig'))
        ;
        $loader = new FilesystemLoader($locator, $parser);
        $loader->addPath(__DIR__.'/../DependencyInjection/Fixtures/Resources/views', 'namespace');

        // Twig-style
        $this->assertEquals("This is a layout\n", $loader->getSource('@namespace/layout.html.twig'));

        // Makhan-style
        $this->assertEquals("This is a layout\n", $loader->getSource('TwigBundle::layout.html.twig'));
    }

    public function testExists()
    {
        // should return true for templates that Twig does not find, but Makhan does
        $parser = $this->getMock('Makhan\Component\Templating\TemplateNameParserInterface');
        $locator = $this->getMock('Makhan\Component\Config\FileLocatorInterface');
        $locator
            ->expects($this->once())
            ->method('locate')
            ->will($this->returnValue($template = __DIR__.'/../DependencyInjection/Fixtures/Resources/views/layout.html.twig'))
        ;
        $loader = new FilesystemLoader($locator, $parser);

        return $this->assertTrue($loader->exists($template));
    }

    /**
     * @expectedException \Twig_Error_Loader
     */
    public function testTwigErrorIfLocatorThrowsInvalid()
    {
        $parser = $this->getMock('Makhan\Component\Templating\TemplateNameParserInterface');
        $parser
            ->expects($this->once())
            ->method('parse')
            ->with('name.format.engine')
            ->will($this->returnValue(new TemplateReference('', '', 'name', 'format', 'engine')))
        ;

        $locator = $this->getMock('Makhan\Component\Config\FileLocatorInterface');
        $locator
            ->expects($this->once())
            ->method('locate')
            ->will($this->throwException(new \InvalidArgumentException('Unable to find template "NonExistent".')))
        ;

        $loader = new FilesystemLoader($locator, $parser);
        $loader->getCacheKey('name.format.engine');
    }

    /**
     * @expectedException \Twig_Error_Loader
     */
    public function testTwigErrorIfLocatorReturnsFalse()
    {
        $parser = $this->getMock('Makhan\Component\Templating\TemplateNameParserInterface');
        $parser
            ->expects($this->once())
            ->method('parse')
            ->with('name.format.engine')
            ->will($this->returnValue(new TemplateReference('', '', 'name', 'format', 'engine')))
        ;

        $locator = $this->getMock('Makhan\Component\Config\FileLocatorInterface');
        $locator
            ->expects($this->once())
            ->method('locate')
            ->will($this->returnValue(false))
        ;

        $loader = new FilesystemLoader($locator, $parser);
        $loader->getCacheKey('name.format.engine');
    }

    /**
     * @expectedException \Twig_Error_Loader
     * @expectedExceptionMessageRegExp /Unable to find template "name\.format\.engine" \(looked into: .*Tests.Loader.\.\..DependencyInjection.Fixtures.Resources.views\)/
     */
    public function testTwigErrorIfTemplateDoesNotExist()
    {
        $parser = $this->getMock('Makhan\Component\Templating\TemplateNameParserInterface');
        $locator = $this->getMock('Makhan\Component\Config\FileLocatorInterface');

        $loader = new FilesystemLoader($locator, $parser);
        $loader->addPath(__DIR__.'/../DependencyInjection/Fixtures/Resources/views');

        $method = new \ReflectionMethod('Makhan\Bundle\TwigBundle\Loader\FilesystemLoader', 'findTemplate');
        $method->setAccessible(true);
        $method->invoke($loader, 'name.format.engine');
    }
}
