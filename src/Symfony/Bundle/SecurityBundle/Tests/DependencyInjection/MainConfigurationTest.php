<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Tests\DependencyInjection;

use Makhan\Bundle\SecurityBundle\DependencyInjection\MainConfiguration;
use Makhan\Component\Config\Definition\Processor;

class MainConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The minimal, required config needed to not have any required validation
     * issues.
     *
     * @var array
     */
    protected static $minimalConfig = array(
        'providers' => array(
            'stub' => array(
                'id' => 'foo',
            ),
        ),
        'firewalls' => array(
            'stub' => array(),
        ),
    );

    /**
     * @expectedException \Makhan\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testNoConfigForProvider()
    {
        $config = array(
            'providers' => array(
                'stub' => array(),
            ),
        );

        $processor = new Processor();
        $configuration = new MainConfiguration(array(), array());
        $processor->processConfiguration($configuration, array($config));
    }

    /**
     * @expectedException \Makhan\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testManyConfigForProvider()
    {
        $config = array(
            'providers' => array(
                'stub' => array(
                    'id' => 'foo',
                    'chain' => array(),
                ),
            ),
        );

        $processor = new Processor();
        $configuration = new MainConfiguration(array(), array());
        $processor->processConfiguration($configuration, array($config));
    }

    public function testCsrfAliases()
    {
        $config = array(
            'firewalls' => array(
                'stub' => array(
                    'logout' => array(
                        'csrf_token_generator' => 'a_token_generator',
                        'csrf_token_id' => 'a_token_id',
                    ),
                ),
            ),
        );
        $config = array_merge(static::$minimalConfig, $config);

        $processor = new Processor();
        $configuration = new MainConfiguration(array(), array());
        $processedConfig = $processor->processConfiguration($configuration, array($config));
        $this->assertTrue(isset($processedConfig['firewalls']['stub']['logout']['csrf_token_generator']));
        $this->assertEquals('a_token_generator', $processedConfig['firewalls']['stub']['logout']['csrf_token_generator']);
        $this->assertTrue(isset($processedConfig['firewalls']['stub']['logout']['csrf_token_id']));
        $this->assertEquals('a_token_id', $processedConfig['firewalls']['stub']['logout']['csrf_token_id']);
    }

    public function testDefaultUserCheckers()
    {
        $processor = new Processor();
        $configuration = new MainConfiguration(array(), array());
        $processedConfig = $processor->processConfiguration($configuration, array(static::$minimalConfig));

        $this->assertEquals('security.user_checker', $processedConfig['firewalls']['stub']['user_checker']);
    }

    public function testUserCheckers()
    {
        $config = array(
            'firewalls' => array(
                'stub' => array(
                    'user_checker' => 'app.henk_checker',
                ),
            ),
        );
        $config = array_merge(static::$minimalConfig, $config);

        $processor = new Processor();
        $configuration = new MainConfiguration(array(), array());
        $processedConfig = $processor->processConfiguration($configuration, array($config));

        $this->assertEquals('app.henk_checker', $processedConfig['firewalls']['stub']['user_checker']);
    }
}
