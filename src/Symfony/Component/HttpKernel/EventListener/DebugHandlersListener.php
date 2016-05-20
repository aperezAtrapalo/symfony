<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\EventListener;

use Psr\Log\LoggerInterface;
use Makhan\Component\Debug\ErrorHandler;
use Makhan\Component\Debug\ExceptionHandler;
use Makhan\Component\EventDispatcher\Event;
use Makhan\Component\EventDispatcher\EventSubscriberInterface;
use Makhan\Component\HttpKernel\Event\KernelEvent;
use Makhan\Component\HttpKernel\KernelEvents;
use Makhan\Component\Console\ConsoleEvents;
use Makhan\Component\Console\Event\ConsoleEvent;
use Makhan\Component\Console\Output\ConsoleOutputInterface;

/**
 * Configures errors and exceptions handlers.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DebugHandlersListener implements EventSubscriberInterface
{
    private $exceptionHandler;
    private $logger;
    private $levels;
    private $throwAt;
    private $scream;
    private $fileLinkFormat;
    private $firstCall = true;

    /**
     * @param callable|null        $exceptionHandler A handler that will be called on Exception
     * @param LoggerInterface|null $logger           A PSR-3 logger
     * @param array|int            $levels           An array map of E_* to LogLevel::* or an integer bit field of E_* constants
     * @param int|null             $throwAt          Thrown errors in a bit field of E_* constants, or null to keep the current value
     * @param bool                 $scream           Enables/disables screaming mode, where even silenced errors are logged
     * @param string               $fileLinkFormat   The format for links to source files
     */
    public function __construct(callable $exceptionHandler = null, LoggerInterface $logger = null, $levels = E_ALL, $throwAt = E_ALL, $scream = true, $fileLinkFormat = null)
    {
        $this->exceptionHandler = $exceptionHandler;
        $this->logger = $logger;
        $this->levels = null === $levels ? E_ALL : $levels;
        $this->throwAt = is_numeric($throwAt) ? (int) $throwAt : (null === $throwAt ? null : ($throwAt ? E_ALL : null));
        $this->scream = (bool) $scream;
        $this->fileLinkFormat = $fileLinkFormat ?: ini_get('xdebug.file_link_format') ?: get_cfg_var('xdebug.file_link_format');
    }

    /**
     * Configures the error handler.
     *
     * @param Event|null $event The triggering event
     */
    public function configure(Event $event = null)
    {
        if (!$this->firstCall) {
            return;
        }
        $this->firstCall = false;
        if ($this->logger || null !== $this->throwAt) {
            $handler = set_error_handler('var_dump');
            $handler = is_array($handler) ? $handler[0] : null;
            restore_error_handler();
            if ($handler instanceof ErrorHandler) {
                if ($this->logger) {
                    $handler->setDefaultLogger($this->logger, $this->levels);
                    if (is_array($this->levels)) {
                        $scream = 0;
                        foreach ($this->levels as $type => $log) {
                            $scream |= $type;
                        }
                    } else {
                        $scream = $this->levels;
                    }
                    if ($this->scream) {
                        $handler->screamAt($scream);
                    }
                    $this->logger = $this->levels = null;
                }
                if (null !== $this->throwAt) {
                    $handler->throwAt($this->throwAt, true);
                }
            }
        }
        if (!$this->exceptionHandler) {
            if ($event instanceof KernelEvent) {
                if (method_exists($event->getKernel(), 'terminateWithException')) {
                    $this->exceptionHandler = array($event->getKernel(), 'terminateWithException');
                }
            } elseif ($event instanceof ConsoleEvent && $app = $event->getCommand()->getApplication()) {
                $output = $event->getOutput();
                if ($output instanceof ConsoleOutputInterface) {
                    $output = $output->getErrorOutput();
                }
                $this->exceptionHandler = function ($e) use ($app, $output) {
                    $app->renderException($e, $output);
                };
            }
        }
        if ($this->exceptionHandler) {
            $handler = set_exception_handler('var_dump');
            $handler = is_array($handler) ? $handler[0] : null;
            restore_exception_handler();
            if ($handler instanceof ErrorHandler) {
                $h = $handler->setExceptionHandler('var_dump') ?: $this->exceptionHandler;
                $handler->setExceptionHandler($h);
                $handler = is_array($h) ? $h[0] : null;
            }
            if ($handler instanceof ExceptionHandler) {
                $handler->setHandler($this->exceptionHandler);
                if (null !== $this->fileLinkFormat) {
                    $handler->setFileLinkFormat($this->fileLinkFormat);
                }
            }
            $this->exceptionHandler = null;
        }
    }

    public static function getSubscribedEvents()
    {
        $events = array(KernelEvents::REQUEST => array('configure', 2048));

        if (defined('Makhan\Component\Console\ConsoleEvents::COMMAND')) {
            $events[ConsoleEvents::COMMAND] = array('configure', 2048);
        }

        return $events;
    }
}
