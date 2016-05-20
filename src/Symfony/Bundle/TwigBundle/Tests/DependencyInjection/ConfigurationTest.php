<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle\Tests\DependencyInjection;

use Makhan\Bundle\TwigBundle\DependencyInjection\Configuration;
use Makhan\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDoNoDuplicateDefaultFormResources()
    {
        $input = array(
            'form_themes' => array('form_div_layout.html.twig'),
        );

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array($input));

        $this->assertEquals(array('form_div_layout.html.twig'), $config['form_themes']);
    }
}
