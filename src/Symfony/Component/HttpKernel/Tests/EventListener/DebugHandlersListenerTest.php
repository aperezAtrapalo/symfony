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

use Psr\Log\LogLevel;
use Makhan\Component\Console\Event\ConsoleEvent;
use Makhan\Component\Console\Command\Command;
use Makhan\Component\Console\ConsoleEvents;
use Makhan\Component\Console\Helper\HelperSet;
use Makhan\Component\Console\Input\ArgvInput;
use Makhan\Component\Console\Output\ConsoleOutput;
use Makhan\Component\Debug\ErrorHandler;
use Makhan\Component\Debug\ExceptionHandler;
use Makhan\Component\EventDispatcher\EventDispatcher;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpKernel\Event\KernelEvent;
use Makhan\Component\HttpKernel\EventListener\DebugHandlersListener;
use Makhan\Component\HttpKernel\HttpKernelInterface;
use Makhan\Component\HttpKernel\KernelEvents;

/**
 * DebugHandlersListenerTest.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DebugHandlersListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigure()
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $userHandler = function () {};
        $listener = new DebugHandlersListener($userHandler, $logger);
        $xHandler = new ExceptionHandler();
        $eHandler = new ErrorHandler();
        $eHandler->setExceptionHandler(array($xHandler, 'handle'));

        $exception = null;
        set_error_handler(array($eHandler, 'handleError'));
        set_exception_handler(array($eHandler, 'handleException'));
        try {
            $listener->configure();
        } catch (\Exception $exception) {
        }
        restore_exception_handler();
        restore_error_handler();

        if (null !== $exception) {
            throw $exception;
        }

        $this->assertSame($userHandler, $xHandler->setHandler('var_dump'));

        $loggers = $eHandler->setLoggers(array());

        $this->assertArrayHasKey(E_DEPRECATED, $loggers);
        $this->assertSame(array($logger, LogLevel::INFO), $loggers[E_DEPRECATED]);
    }

    public function testConfigureForHttpKernelWithNoTerminateWithException()
    {
        $listener = new DebugHandlersListener(null);
        $eHandler = new ErrorHandler();
        $event = new KernelEvent(
            $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface'),
            Request::create('/'),
            HttpKernelInterface::MASTER_REQUEST
        );

        $exception = null;
        $h = set_exception_handler(array($eHandler, 'handleException'));
        try {
            $listener->configure($event);
        } catch (\Exception $exception) {
        }
        restore_exception_handler();

        if (null !== $exception) {
            throw $exception;
        }

        $this->assertNull($h);
    }

    public function testConsoleEvent()
    {
        $dispatcher = new EventDispatcher();
        $listener = new DebugHandlersListener(null);
        $app = $this->getMock('Makhan\Component\Console\Application');
        $app->expects($this->once())->method('getHelperSet')->will($this->returnValue(new HelperSet()));
        $command = new Command(__FUNCTION__);
        $command->setApplication($app);
        $event = new ConsoleEvent($command, new ArgvInput(), new ConsoleOutput());

        $dispatcher->addSubscriber($listener);

        $xListeners = array(
            KernelEvents::REQUEST => array(array($listener, 'configure')),
            ConsoleEvents::COMMAND => array(array($listener, 'configure')),
        );
        $this->assertSame($xListeners, $dispatcher->getListeners());

        $exception = null;
        $eHandler = new ErrorHandler();
        set_error_handler(array($eHandler, 'handleError'));
        set_exception_handler(array($eHandler, 'handleException'));
        try {
            $dispatcher->dispatch(ConsoleEvents::COMMAND, $event);
        } catch (\Exception $exception) {
        }
        restore_exception_handler();
        restore_error_handler();

        if (null !== $exception) {
            throw $exception;
        }

        $xHandler = $eHandler->setExceptionHandler('var_dump');
        $this->assertInstanceOf('Closure', $xHandler);

        $app->expects($this->once())
            ->method('renderException');

        $xHandler(new \Exception());
    }
}
