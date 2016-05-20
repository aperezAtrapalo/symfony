<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Controller;

use Psr\Log\LoggerInterface;
use Makhan\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;
use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\DependencyInjection\ContainerAwareInterface;

/**
 * ControllerResolver.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class ControllerResolver extends BaseControllerResolver
{
    protected $container;
    protected $parser;

    /**
     * Constructor.
     *
     * @param ContainerInterface   $container A ContainerInterface instance
     * @param ControllerNameParser $parser    A ControllerNameParser instance
     * @param LoggerInterface      $logger    A LoggerInterface instance
     */
    public function __construct(ContainerInterface $container, ControllerNameParser $parser, LoggerInterface $logger = null)
    {
        $this->container = $container;
        $this->parser = $parser;

        parent::__construct($logger);
    }

    /**
     * Returns a callable for the given controller.
     *
     * @param string $controller A Controller string
     *
     * @return mixed A PHP callable
     *
     * @throws \LogicException           When the name could not be parsed
     * @throws \InvalidArgumentException When the controller class does not exist
     */
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            $count = substr_count($controller, ':');
            if (2 == $count) {
                // controller in the a:b:c notation then
                $controller = $this->parser->parse($controller);
            } elseif (1 == $count) {
                // controller in the service:method notation
                list($service, $method) = explode(':', $controller, 2);

                return array($this->container->get($service), $method);
            } elseif ($this->container->has($controller) && method_exists($service = $this->container->get($controller), '__invoke')) {
                return $service;
            } else {
                throw new \LogicException(sprintf('Unable to parse the controller name "%s".', $controller));
            }
        }

        return parent::createController($controller);
    }

    /**
     * {@inheritdoc}
     */
    protected function instantiateController($class)
    {
        if ($this->container->has($class)) {
            return $this->container->get($class);
        }

        $controller = parent::instantiateController($class);

        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return $controller;
    }
}
