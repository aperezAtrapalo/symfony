<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\DependencyInjection;

use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\HttpFoundation\RequestStack;
use Makhan\Component\HttpKernel\Fragment\FragmentHandler;

/**
 * Lazily loads fragment renderers from the dependency injection container.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class LazyLoadingFragmentHandler extends FragmentHandler
{
    private $container;
    private $rendererIds = array();

    /**
     * Constructor.
     *
     * @param ContainerInterface $container    A container
     * @param RequestStack       $requestStack The Request stack that controls the lifecycle of requests
     * @param bool               $debug        Whether the debug mode is enabled or not
     */
    public function __construct(ContainerInterface $container, RequestStack $requestStack, $debug = false)
    {
        $this->container = $container;

        parent::__construct($requestStack, array(), $debug);
    }

    /**
     * Adds a service as a fragment renderer.
     *
     * @param string $renderer The render service id
     */
    public function addRendererService($name, $renderer)
    {
        $this->rendererIds[$name] = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function render($uri, $renderer = 'inline', array $options = array())
    {
        if (isset($this->rendererIds[$renderer])) {
            $this->addRenderer($this->container->get($this->rendererIds[$renderer]));

            unset($this->rendererIds[$renderer]);
        }

        return parent::render($uri, $renderer, $options);
    }
}
