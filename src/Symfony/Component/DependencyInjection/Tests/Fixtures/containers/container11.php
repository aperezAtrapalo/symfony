<?php

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Definition;

$container = new ContainerBuilder();
$container->
    register('foo', 'FooClass')->
    addArgument(new Definition('BarClass', array(new Definition('BazClass'))))
;

return $container;
