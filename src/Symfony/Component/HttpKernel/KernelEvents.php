<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel;

/**
 * Contains all events thrown in the HttpKernel component.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class KernelEvents
{
    /**
     * The REQUEST event occurs at the very beginning of request
     * dispatching.
     *
     * This event allows you to create a response for a request before any
     * other code in the framework is executed.
     *
     * @Event("Makhan\Component\HttpKernel\Event\GetResponseEvent")
     *
     * @var string
     */
    const REQUEST = 'kernel.request';

    /**
     * The EXCEPTION event occurs when an uncaught exception appears.
     *
     * This event allows you to create a response for a thrown exception or
     * to modify the thrown exception.
     *
     * @Event("Makhan\Component\HttpKernel\Event\GetResponseForExceptionEvent")
     *
     * @var string
     */
    const EXCEPTION = 'kernel.exception';

    /**
     * The VIEW event occurs when the return value of a controller
     * is not a Response instance.
     *
     * This event allows you to create a response for the return value of the
     * controller.
     *
     * @Event("Makhan\Component\HttpKernel\Event\GetResponseForControllerResultEvent")
     *
     * @var string
     */
    const VIEW = 'kernel.view';

    /**
     * The CONTROLLER event occurs once a controller was found for
     * handling a request.
     *
     * This event allows you to change the controller that will handle the
     * request.
     *
     * @Event("Makhan\Component\HttpKernel\Event\FilterControllerEvent")
     *
     * @var string
     */
    const CONTROLLER = 'kernel.controller';

    /**
     * The CONTROLLER_ARGUMENTS event occurs once controller arguments have been resolved.
     *
     * This event allows you to change the arguments that will be passed to
     * the controller. The event listener method receives a
     * Makhan\Component\HttpKernel\Event\FilterControllerArgumentsEvent instance.
     *
     * @Event
     *
     * @var string
     */
    const CONTROLLER_ARGUMENTS = 'kernel.controller_arguments';

    /**
     * The RESPONSE event occurs once a response was created for
     * replying to a request.
     *
     * This event allows you to modify or replace the response that will be
     * replied.
     *
     * @Event("Makhan\Component\HttpKernel\Event\FilterResponseEvent")
     *
     * @var string
     */
    const RESPONSE = 'kernel.response';

    /**
     * The TERMINATE event occurs once a response was sent.
     *
     * This event allows you to run expensive post-response jobs.
     *
     * @Event("Makhan\Component\HttpKernel\Event\PostResponseEvent")
     *
     * @var string
     */
    const TERMINATE = 'kernel.terminate';

    /**
     * The FINISH_REQUEST event occurs when a response was generated for a request.
     *
     * This event allows you to reset the global and environmental state of
     * the application, when it was changed during the request.
     *
     * @Event("Makhan\Component\HttpKernel\Event\FinishRequestEvent")
     *
     * @var string
     */
    const FINISH_REQUEST = 'kernel.finish_request';
}
