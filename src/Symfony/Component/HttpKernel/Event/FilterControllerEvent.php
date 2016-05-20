<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Event;

use Makhan\Component\HttpKernel\HttpKernelInterface;
use Makhan\Component\HttpFoundation\Request;

/**
 * Allows filtering of a controller callable.
 *
 * You can call getController() to retrieve the current controller. With
 * setController() you can set a new controller that is used in the processing
 * of the request.
 *
 * Controllers should be callables.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FilterControllerEvent extends KernelEvent
{
    /**
     * The current controller.
     */
    private $controller;

    public function __construct(HttpKernelInterface $kernel, callable $controller, Request $request, $requestType)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->setController($controller);
    }

    /**
     * Returns the current controller.
     *
     * @return callable
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets a new controller.
     *
     * @param callable $controller
     */
    public function setController(callable $controller)
    {
        $this->controller = $controller;
    }
}
