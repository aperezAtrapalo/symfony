<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\Fragment;

use Makhan\Component\HttpKernel\Controller\ControllerReference;
use Makhan\Component\HttpKernel\Fragment\HIncludeFragmentRenderer;
use Makhan\Component\HttpKernel\UriSigner;
use Makhan\Component\HttpFoundation\Request;

class HIncludeFragmentRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testRenderExceptionWhenControllerAndNoSigner()
    {
        $strategy = new HIncludeFragmentRenderer();
        $strategy->render(new ControllerReference('main_controller', array(), array()), Request::create('/'));
    }

    public function testRenderWithControllerAndSigner()
    {
        $strategy = new HIncludeFragmentRenderer(null, new UriSigner('foo'));

        $this->assertEquals('<hx:include src="/_fragment?_path=_format%3Dhtml%26_locale%3Den%26_controller%3Dmain_controller&amp;_hash=BP%2BOzCD5MRUI%2BHJpgPDOmoju00FnzLhP3TGcSHbbBLs%3D"></hx:include>', $strategy->render(new ControllerReference('main_controller', array(), array()), Request::create('/'))->getContent());
    }

    public function testRenderWithUri()
    {
        $strategy = new HIncludeFragmentRenderer();
        $this->assertEquals('<hx:include src="/foo"></hx:include>', $strategy->render('/foo', Request::create('/'))->getContent());

        $strategy = new HIncludeFragmentRenderer(null, new UriSigner('foo'));
        $this->assertEquals('<hx:include src="/foo"></hx:include>', $strategy->render('/foo', Request::create('/'))->getContent());
    }

    public function testRenderWithDefault()
    {
        // only default
        $strategy = new HIncludeFragmentRenderer();
        $this->assertEquals('<hx:include src="/foo">default</hx:include>', $strategy->render('/foo', Request::create('/'), array('default' => 'default'))->getContent());

        // only global default
        $strategy = new HIncludeFragmentRenderer(null, null, 'global_default');
        $this->assertEquals('<hx:include src="/foo">global_default</hx:include>', $strategy->render('/foo', Request::create('/'), array())->getContent());

        // global default and default
        $strategy = new HIncludeFragmentRenderer(null, null, 'global_default');
        $this->assertEquals('<hx:include src="/foo">default</hx:include>', $strategy->render('/foo', Request::create('/'), array('default' => 'default'))->getContent());
    }

    public function testRenderWithAttributesOptions()
    {
        // with id
        $strategy = new HIncludeFragmentRenderer();
        $this->assertEquals('<hx:include src="/foo" id="bar">default</hx:include>', $strategy->render('/foo', Request::create('/'), array('default' => 'default', 'id' => 'bar'))->getContent());

        // with attributes
        $strategy = new HIncludeFragmentRenderer();
        $this->assertEquals('<hx:include src="/foo" p1="v1" p2="v2">default</hx:include>', $strategy->render('/foo', Request::create('/'), array('default' => 'default', 'attributes' => array('p1' => 'v1', 'p2' => 'v2')))->getContent());

        // with id & attributes
        $strategy = new HIncludeFragmentRenderer();
        $this->assertEquals('<hx:include src="/foo" p1="v1" p2="v2" id="bar">default</hx:include>', $strategy->render('/foo', Request::create('/'), array('default' => 'default', 'id' => 'bar', 'attributes' => array('p1' => 'v1', 'p2' => 'v2')))->getContent());
    }

    public function testRenderWithDefaultText()
    {
        $engine = $this->getMock('Makhan\\Component\\Templating\\EngineInterface');
        $engine->expects($this->once())
            ->method('exists')
            ->with('default')
            ->will($this->throwException(new \InvalidArgumentException()));

        // only default
        $strategy = new HIncludeFragmentRenderer($engine);
        $this->assertEquals('<hx:include src="/foo">default</hx:include>', $strategy->render('/foo', Request::create('/'), array('default' => 'default'))->getContent());
    }
}
