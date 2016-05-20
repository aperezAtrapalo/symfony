<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Functional;

class ProfilerTest extends WebTestCase
{
    /**
     * @dataProvider getConfigs
     */
    public function testProfilerIsDisabled($insulate)
    {
        $client = $this->createClient(array('test_case' => 'Profiler', 'root_config' => 'config.yml'));
        if ($insulate) {
            $client->insulate();
        }

        $client->request('GET', '/profiler');
        $this->assertFalse($client->getProfile());

        // enable the profiler for the next request
        $client->enableProfiler();
        $crawler = $client->request('GET', '/profiler');
        $profile = $client->getProfile();
        $this->assertTrue(is_object($profile));

        $client->request('GET', '/profiler');
        $this->assertFalse($client->getProfile());
    }

    public function getConfigs()
    {
        return array(
            array(false),
            array(true),
        );
    }
}
