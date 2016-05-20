<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\Tests\Loader;

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Loader\PhpFileLoader;
use Makhan\Component\DependencyInjection\Loader\IniFileLoader;
use Makhan\Component\DependencyInjection\Loader\YamlFileLoader;
use Makhan\Component\DependencyInjection\Loader\DirectoryLoader;
use Makhan\Component\Config\Loader\LoaderResolver;
use Makhan\Component\Config\FileLocator;

class DirectoryLoaderTest extends \PHPUnit_Framework_TestCase
{
    private static $fixturesPath;

    private $container;
    private $loader;

    public static function setUpBeforeClass()
    {
        self::$fixturesPath = realpath(__DIR__.'/../Fixtures/');
    }

    protected function setUp()
    {
        $locator = new FileLocator(self::$fixturesPath);
        $this->container = new ContainerBuilder();
        $this->loader = new DirectoryLoader($this->container, $locator);
        $resolver = new LoaderResolver(array(
            new PhpFileLoader($this->container, $locator),
            new IniFileLoader($this->container, $locator),
            new YamlFileLoader($this->container, $locator),
            $this->loader,
        ));
        $this->loader->setResolver($resolver);
    }

    public function testDirectoryCanBeLoadedRecursively()
    {
        $this->loader->load('directory/');
        $this->assertEquals(array('ini' => 'ini', 'yaml' => 'yaml', 'php' => 'php'), $this->container->getParameterBag()->all(), '->load() takes a single directory');
    }

    public function testImports()
    {
        $this->loader->resolve('directory/import/import.yml')->load('directory/import/import.yml');
        $this->assertEquals(array('ini' => 'ini', 'yaml' => 'yaml'), $this->container->getParameterBag()->all(), '->load() takes a single file that imports a directory');
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The file "foo" does not exist (in:
     */
    public function testExceptionIsRaisedWhenDirectoryDoesNotExist()
    {
        $this->loader->load('foo/');
    }

    public function testSupports()
    {
        $loader = new DirectoryLoader(new ContainerBuilder(), new FileLocator());

        $this->assertTrue($loader->supports('directory/'), '->supports("directory/") returns true');
        $this->assertTrue($loader->supports('directory/', 'directory'), '->supports("directory/", "directory") returns true');
        $this->assertFalse($loader->supports('directory'), '->supports("directory") returns false');
        $this->assertTrue($loader->supports('directory', 'directory'), '->supports("directory", "directory") returns true');
        $this->assertFalse($loader->supports('directory', 'foo'), '->supports("directory", "foo") returns false');
    }
}
