<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Templating;

use Makhan\Component\Templating\PhpEngine as BasePhpEngine;
use Makhan\Component\Templating\Loader\LoaderInterface;
use Makhan\Component\Templating\TemplateNameParserInterface;
use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\HttpFoundation\Response;

/**
 * This engine knows how to render Makhan templates.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class PhpEngine extends BasePhpEngine implements EngineInterface
{
    protected $container;

    /**
     * Constructor.
     *
     * @param TemplateNameParserInterface $parser    A TemplateNameParserInterface instance
     * @param ContainerInterface          $container The DI container
     * @param LoaderInterface             $loader    A loader instance
     * @param GlobalVariables|null        $globals   A GlobalVariables instance or null
     */
    public function __construct(TemplateNameParserInterface $parser, ContainerInterface $container, LoaderInterface $loader, GlobalVariables $globals = null)
    {
        $this->container = $container;

        parent::__construct($parser, $loader);

        if (null !== $globals) {
            $this->addGlobal('app', $globals);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (!isset($this->helpers[$name])) {
            throw new \InvalidArgumentException(sprintf('The helper "%s" is not defined.', $name));
        }

        if (is_string($this->helpers[$name])) {
            $this->helpers[$name] = $this->container->get($this->helpers[$name]);
            $this->helpers[$name]->setCharset($this->charset);
        }

        return $this->helpers[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function setHelpers(array $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * {@inheritdoc}
     */
    public function renderResponse($view, array $parameters = array(), Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->render($view, $parameters));

        return $response;
    }
}
