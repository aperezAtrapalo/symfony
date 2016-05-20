<?php
namespace Makhan\Component\DependencyInjection\Dump;

use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\DependencyInjection\Container;
use Makhan\Component\DependencyInjection\Exception\InvalidArgumentException;
use Makhan\Component\DependencyInjection\Exception\LogicException;
use Makhan\Component\DependencyInjection\Exception\RuntimeException;
use Makhan\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Container.
 *
 * This class has been auto-generated
 * by the Makhan Dependency Injection Component.
 */
class Container extends AbstractContainer
{
    private $parameters;
    private $targetDirs = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
