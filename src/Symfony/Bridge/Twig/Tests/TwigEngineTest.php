<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Twig\Tests;

use Makhan\Bridge\Twig\TwigEngine;
use Makhan\Component\Templating\TemplateReference;

class TwigEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testExistsWithTemplateInstances()
    {
        $engine = $this->getTwig();

        $this->assertTrue($engine->exists($this->getMockForAbstractClass('Twig_Template', array(), '', false)));
    }

    public function testExistsWithNonExistentTemplates()
    {
        $engine = $this->getTwig();

        $this->assertFalse($engine->exists('foobar'));
        $this->assertFalse($engine->exists(new TemplateReference('foorbar')));
    }

    public function testExistsWithTemplateWithSyntaxErrors()
    {
        $engine = $this->getTwig();

        $this->assertTrue($engine->exists('error'));
        $this->assertTrue($engine->exists(new TemplateReference('error')));
    }

    public function testExists()
    {
        $engine = $this->getTwig();

        $this->assertTrue($engine->exists('index'));
        $this->assertTrue($engine->exists(new TemplateReference('index')));
    }

    public function testRender()
    {
        $engine = $this->getTwig();

        $this->assertSame('foo', $engine->render('index'));
        $this->assertSame('foo', $engine->render(new TemplateReference('index')));
    }

    /**
     * @expectedException \Twig_Error_Syntax
     */
    public function testRenderWithError()
    {
        $engine = $this->getTwig();

        $engine->render(new TemplateReference('error'));
    }

    protected function getTwig()
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Array(array(
            'index' => 'foo',
            'error' => '{{ foo }',
        )));
        $parser = $this->getMock('Makhan\Component\Templating\TemplateNameParserInterface');

        return new TwigEngine($twig, $parser);
    }
}
