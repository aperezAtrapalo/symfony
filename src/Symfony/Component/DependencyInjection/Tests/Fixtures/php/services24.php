<?php

use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\DependencyInjection\Container;
use Makhan\Component\DependencyInjection\Exception\InvalidArgumentException;
use Makhan\Component\DependencyInjection\Exception\LogicException;
use Makhan\Component\DependencyInjection\Exception\RuntimeException;
use Makhan\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * ProjectServiceContainer.
 *
 * This class has been auto-generated
 * by the Makhan Dependency Injection Component.
 */
class ProjectServiceContainer extends Container
{
    private $parameters;
    private $targetDirs = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->methodMap = array(
            'foo' => 'getFooService',
        );
    }

    /**
     * Gets the 'foo' service.
     *
     * This service is autowired.
     *
     * @return \Foo A Foo instance.
     */
    protected function getFooService()
    {
        return $this->services['foo'] = new \Foo();
    }
}
