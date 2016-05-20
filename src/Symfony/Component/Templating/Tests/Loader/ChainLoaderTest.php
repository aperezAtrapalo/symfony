<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Templating\Tests\Loader;

use Makhan\Component\Templating\Loader\ChainLoader;
use Makhan\Component\Templating\Loader\FilesystemLoader;
use Makhan\Component\Templating\TemplateReference;

class ChainLoaderTest extends \PHPUnit_Framework_TestCase
{
    protected $loader1;
    protected $loader2;

    protected function setUp()
    {
        $fixturesPath = realpath(__DIR__.'/../Fixtures/');
        $this->loader1 = new FilesystemLoader($fixturesPath.'/null/%name%');
        $this->loader2 = new FilesystemLoader($fixturesPath.'/templates/%name%');
    }

    public function testConstructor()
    {
        $loader = new ProjectTemplateLoader1(array($this->loader1, $this->loader2));
        $this->assertEquals(array($this->loader1, $this->loader2), $loader->getLoaders(), '__construct() takes an array of template loaders as its second argument');
    }

    public function testAddLoader()
    {
        $loader = new ProjectTemplateLoader1(array($this->loader1));
        $loader->addLoader($this->loader2);
        $this->assertEquals(array($this->loader1, $this->loader2), $loader->getLoaders(), '->addLoader() adds a template loader at the end of the loaders');
    }

    public function testLoad()
    {
        $loader = new ProjectTemplateLoader1(array($this->loader1, $this->loader2));
        $this->assertFalse($loader->load(new TemplateReference('bar', 'php')), '->load() returns false if the template is not found');
        $this->assertFalse($loader->load(new TemplateReference('foo', 'php')), '->load() returns false if the template does not exist for the given renderer');
        $this->assertInstanceOf(
            'Makhan\Component\Templating\Storage\FileStorage',
            $loader->load(new TemplateReference('foo.php', 'php')),
            '->load() returns a FileStorage if the template exists'
        );
    }
}

class ProjectTemplateLoader1 extends ChainLoader
{
    public function getLoaders()
    {
        return $this->loaders;
    }
}
