<?php

namespace Makhan\Bundle\FrameworkBundle\Tests\Routing;

use Makhan\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Makhan\Bundle\FrameworkBundle\Routing\DelegatingLoader;
use Makhan\Component\Config\Loader\LoaderResolver;

class DelegatingLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorApi()
    {
        $controllerNameParser = $this->getMockBuilder(ControllerNameParser::class)
            ->disableOriginalConstructor()
            ->getMock();
        new DelegatingLoader($controllerNameParser, new LoaderResolver());
        $this->assertTrue(true, '__construct() takes a ControllerNameParser and LoaderResolverInterface respectively as its first and second argument.');
    }
}
