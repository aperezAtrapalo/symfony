<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console\Event;

use Makhan\Component\Console\Command\Command;
use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;

/**
 * Allows to handle exception thrown in a command.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class ConsoleExceptionEvent extends ConsoleEvent
{
    private $exception;
    private $exitCode;

    public function __construct(Command $command, InputInterface $input, OutputInterface $output, \Exception $exception, $exitCode)
    {
        parent::__construct($command, $input, $output);

        $this->setException($exception);
        $this->exitCode = (int) $exitCode;
    }

    /**
     * Returns the thrown exception.
     *
     * @return \Exception The thrown exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Replaces the thrown exception.
     *
     * This exception will be thrown if no response is set in the event.
     *
     * @param \Exception $exception The thrown exception
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Gets the exit code.
     *
     * @return int The command exit code
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}
