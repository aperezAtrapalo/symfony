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

use Makhan\Bundle\FrameworkBundle\Console\Application;
use Makhan\Component\Console\Input\ArrayInput;
use Makhan\Component\Console\Output\NullOutput;
use Makhan\Component\Console\Tester\CommandTester;

/**
 * @group functional
 */
class ConfigDumpReferenceCommandTest extends WebTestCase
{
    private $application;

    protected function setUp()
    {
        $kernel = static::createKernel(array('test_case' => 'ConfigDump', 'root_config' => 'config.yml'));
        $this->application = new Application($kernel);
        $this->application->doRun(new ArrayInput(array()), new NullOutput());
    }

    public function testDumpBundleName()
    {
        $tester = $this->createCommandTester();
        $ret = $tester->execute(array('name' => 'TestBundle'));

        $this->assertSame(0, $ret, 'Returns 0 in case of success');
        $this->assertContains('test:', $tester->getDisplay());
        $this->assertContains('    custom:', $tester->getDisplay());
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester()
    {
        $command = $this->application->find('config:dump-reference');

        return new CommandTester($command);
    }
}
