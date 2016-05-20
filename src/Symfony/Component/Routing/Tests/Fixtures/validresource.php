<?php

/** @var $loader \Makhan\Component\Routing\Loader\PhpFileLoader */
/** @var \Makhan\Component\Routing\RouteCollection $collection */
$collection = $loader->import('validpattern.php');
$collection->addDefaults(array(
    'foo' => 123,
));
$collection->addRequirements(array(
    'foo' => '\d+',
));
$collection->addOptions(array(
    'foo' => 'bar',
));
$collection->setCondition('context.getMethod() == "POST"');
$collection->addPrefix('/prefix');

return $collection;
