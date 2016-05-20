<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console;

/**
 * Contains all events dispatched by an Application.
 *
 * @author Francesco Levorato <git@flevour.net>
 */
final class ConsoleEvents
{
    /**
     * The COMMAND event allows you to attach listeners before any command is
     * executed by the console. It also allows you to modify the command, input and output
     * before they are handled to the command.
     *
     * @Event("Makhan\Component\Console\Event\ConsoleCommandEvent")
     *
     * @var string
     */
    const COMMAND = 'console.command';

    /**
     * The TERMINATE event allows you to attach listeners after a command is
     * executed by the console.
     *
     * @Event("Makhan\Component\Console\Event\ConsoleTerminateEvent")
     *
     * @var string
     */
    const TERMINATE = 'console.terminate';

    /**
     * The EXCEPTION event occurs when an uncaught exception appears.
     *
     * This event allows you to deal with the exception or
     * to modify the thrown exception.
     *
     * @Event("Makhan\Component\Console\Event\ConsoleExceptionEvent")
     *
     * @var string
     */
    const EXCEPTION = 'console.exception';
}
