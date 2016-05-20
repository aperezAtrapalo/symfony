<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Templating;

use Makhan\Bundle\FrameworkBundle\Tests\TestCase;
use Makhan\Bundle\FrameworkBundle\Templating\TemplateReference;

class TemplateReferenceTest extends TestCase
{
    public function testGetPathWorksWithNamespacedControllers()
    {
        $reference = new TemplateReference('AcmeBlogBundle', 'Admin\Post', 'index', 'html', 'twig');

        $this->assertSame(
            '@AcmeBlogBundle/Resources/views/Admin/Post/index.html.twig',
            $reference->getPath()
        );
    }
}
