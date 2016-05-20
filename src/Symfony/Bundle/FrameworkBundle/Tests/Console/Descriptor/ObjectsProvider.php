<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Console\Descriptor;

use Makhan\Component\DependencyInjection\Alias;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Definition;
use Makhan\Component\DependencyInjection\ParameterBag\ParameterBag;
use Makhan\Component\DependencyInjection\Reference;
use Makhan\Component\EventDispatcher\EventDispatcher;
use Makhan\Component\Routing\Route;
use Makhan\Component\Routing\RouteCollection;

class ObjectsProvider
{
    public static function getRouteCollections()
    {
        $collection1 = new RouteCollection();
        foreach (self::getRoutes() as $name => $route) {
            $collection1->add($name, $route);
        }

        return array('route_collection_1' => $collection1);
    }

    public static function getRoutes()
    {
        return array(
            'route_1' => new Route(
                '/hello/{name}',
                array('name' => 'Joseph'),
                array('name' => '[a-z]+'),
                array('opt1' => 'val1', 'opt2' => 'val2'),
                'localhost',
                array('http', 'https'),
                array('get', 'head')
            ),
            'route_2' => new Route(
                '/name/add',
                array(),
                array(),
                array('opt1' => 'val1', 'opt2' => 'val2'),
                'localhost',
                array('http', 'https'),
                array('put', 'post')
            ),
        );
    }

    public static function getContainerParameters()
    {
        return array(
            'parameters_1' => new ParameterBag(array(
                'integer' => 12,
                'string' => 'Hello world!',
                'boolean' => true,
                'array' => array(12, 'Hello world!', true),
            )),
        );
    }

    public static function getContainerParameter()
    {
        $builder = new ContainerBuilder();
        $builder->setParameter('database_name', 'makhan');
        $builder->setParameter('twig.form.resources', array(
            'bootstrap_3_horizontal_layout.html.twig',
            'bootstrap_3_layout.html.twig',
            'form_div_layout.html.twig',
            'form_table_layout.html.twig',
        ));

        return array(
            'parameter' => $builder,
            'array_parameter' => $builder,
        );
    }

    public static function getContainerBuilders()
    {
        $builder1 = new ContainerBuilder();
        $builder1->setDefinitions(self::getContainerDefinitions());
        $builder1->setAliases(self::getContainerAliases());

        return array('builder_1' => $builder1);
    }

    public static function getContainerDefinitions()
    {
        $definition1 = new Definition('Full\\Qualified\\Class1');
        $definition2 = new Definition('Full\\Qualified\\Class2');

        return array(
            'definition_1' => $definition1
                ->setPublic(true)
                ->setSynthetic(false)
                ->setLazy(true)
                ->setAbstract(true)
                ->setFactory(array('Full\\Qualified\\FactoryClass', 'get')),
            'definition_2' => $definition2
                ->setPublic(false)
                ->setSynthetic(true)
                ->setFile('/path/to/file')
                ->setLazy(false)
                ->setAbstract(false)
                ->addTag('tag1', array('attr1' => 'val1', 'attr2' => 'val2'))
                ->addTag('tag1', array('attr3' => 'val3'))
                ->addTag('tag2')
                ->addMethodCall('setMailer', array(new Reference('mailer')))
                ->setFactory(array(new Reference('factory.service'), 'get')),
        );
    }

    public static function getContainerAliases()
    {
        return array(
            'alias_1' => new Alias('service_1', true),
            'alias_2' => new Alias('service_2', false),
        );
    }

    public static function getEventDispatchers()
    {
        $eventDispatcher = new EventDispatcher();

        $eventDispatcher->addListener('event1', 'global_function', 255);
        $eventDispatcher->addListener('event1', function () { return 'Closure'; }, -1);
        $eventDispatcher->addListener('event2', new CallableClass());

        return array('event_dispatcher_1' => $eventDispatcher);
    }

    public static function getCallables()
    {
        return array(
            'callable_1' => 'array_key_exists',
            'callable_2' => array('Makhan\\Bundle\\FrameworkBundle\\Tests\\Console\\Descriptor\\CallableClass', 'staticMethod'),
            'callable_3' => array(new CallableClass(), 'method'),
            'callable_4' => 'Makhan\\Bundle\\FrameworkBundle\\Tests\\Console\\Descriptor\\CallableClass::staticMethod',
            'callable_5' => array('Makhan\\Bundle\\FrameworkBundle\\Tests\\Console\\Descriptor\\ExtendedCallableClass', 'parent::staticMethod'),
            'callable_6' => function () { return 'Closure'; },
            'callable_7' => new CallableClass(),
        );
    }
}

class CallableClass
{
    public function __invoke()
    {
    }
    public static function staticMethod()
    {
    }
    public function method()
    {
    }
}

class ExtendedCallableClass extends CallableClass
{
    public static function staticMethod()
    {
    }
}
