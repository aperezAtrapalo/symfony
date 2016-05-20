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
            'service_from_anonymous_factory' => 'getServiceFromAnonymousFactoryService',
            'service_with_method_call_and_factory' => 'getServiceWithMethodCallAndFactoryService',
        );
    }

    /**
     * Gets the 'service_from_anonymous_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \Bar\FooClass A Bar\FooClass instance.
     */
    protected function getServiceFromAnonymousFactoryService()
    {
        return $this->services['service_from_anonymous_factory'] = call_user_func(array(new \Bar\FooClass(), 'getInstance'));
    }

    /**
     * Gets the 'service_with_method_call_and_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \Bar\FooClass A Bar\FooClass instance.
     */
    protected function getServiceWithMethodCallAndFactoryService()
    {
        $this->services['service_with_method_call_and_factory'] = $instance = new \Bar\FooClass();

        $instance->setBar(\Bar\FooClass::getInstance());

        return $instance;
    }
}
