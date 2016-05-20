<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\EventListener;

use Makhan\Component\HttpKernel\EventListener\DumpListener;
use Makhan\Component\HttpKernel\KernelEvents;
use Makhan\Component\VarDumper\Cloner\ClonerInterface;
use Makhan\Component\VarDumper\Cloner\Data;
use Makhan\Component\VarDumper\Dumper\DataDumperInterface;
use Makhan\Component\VarDumper\VarDumper;

/**
 * DumpListenerTest.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DumpListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscribedEvents()
    {
        $this->assertSame(
            array(KernelEvents::REQUEST => array('configure', 1024)),
            DumpListener::getSubscribedEvents()
        );
    }

    public function testConfigure()
    {
        $prevDumper = VarDumper::setHandler('var_dump');
        VarDumper::setHandler($prevDumper);

        $cloner = new MockCloner();
        $dumper = new MockDumper();

        ob_start();
        $exception = null;
        $listener = new DumpListener($cloner, $dumper);

        try {
            $listener->configure();

            VarDumper::dump('foo');
            VarDumper::dump('bar');

            $this->assertSame('+foo-+bar-', ob_get_clean());
        } catch (\Exception $exception) {
        }

        VarDumper::setHandler($prevDumper);

        if (null !== $exception) {
            throw $exception;
        }
    }
}

class MockCloner implements ClonerInterface
{
    public function cloneVar($var)
    {
        return new Data(array($var.'-'));
    }
}

class MockDumper implements DataDumperInterface
{
    public function dump(Data $data)
    {
        $rawData = $data->getRawData();

        echo '+'.$rawData[0];
    }
}
