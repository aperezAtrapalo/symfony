<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\WebProfilerBundle\Tests\DependencyInjection;

use Makhan\Bundle\WebProfilerBundle\DependencyInjection\Configuration;
use Makhan\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDebugModes
     */
    public function testConfigTree($options, $results)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, array($options));

        $this->assertEquals($results, $config);
    }

    public function getDebugModes()
    {
        return array(
            array(array(), array('intercept_redirects' => false, 'toolbar' => false, 'position' => 'bottom', 'excluded_ajax_paths' => '^/(app(_[\\w]+)?\\.php/)?_wdt')),
            array(array('intercept_redirects' => true), array('intercept_redirects' => true, 'toolbar' => false, 'position' => 'bottom', 'excluded_ajax_paths' => '^/(app(_[\\w]+)?\\.php/)?_wdt')),
            array(array('intercept_redirects' => false), array('intercept_redirects' => false, 'toolbar' => false, 'position' => 'bottom', 'excluded_ajax_paths' => '^/(app(_[\\w]+)?\\.php/)?_wdt')),
            array(array('toolbar' => true), array('intercept_redirects' => false, 'toolbar' => true, 'position' => 'bottom', 'excluded_ajax_paths' => '^/(app(_[\\w]+)?\\.php/)?_wdt')),
            array(array('position' => 'top'), array('intercept_redirects' => false, 'toolbar' => false, 'position' => 'top', 'excluded_ajax_paths' => '^/(app(_[\\w]+)?\\.php/)?_wdt')),
            array(array('excluded_ajax_paths' => 'test'), array('intercept_redirects' => false, 'toolbar' => false, 'position' => 'bottom', 'excluded_ajax_paths' => 'test')),
        );
    }
}
