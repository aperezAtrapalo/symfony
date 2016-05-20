<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\Tests\Compiler;

use Makhan\Component\DependencyInjection\Compiler\AutoAliasServicePass;
use Makhan\Component\DependencyInjection\ContainerBuilder;

class AutoAliasServicePassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Makhan\Component\DependencyInjection\Exception\ParameterNotFoundException
     */
    public function testProcessWithMissingParameter()
    {
        $container = new ContainerBuilder();

        $container->register('example')
          ->addTag('auto_alias', array('format' => '%non_existing%.example'));

        $pass = new AutoAliasServicePass();
        $pass->process($container);
    }

    /**
     * @expectedException \Makhan\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function testProcessWithMissingFormat()
    {
        $container = new ContainerBuilder();

        $container->register('example')
          ->addTag('auto_alias', array());
        $container->setParameter('existing', 'mysql');

        $pass = new AutoAliasServicePass();
        $pass->process($container);
    }

    public function testProcessWithNonExistingAlias()
    {
        $container = new ContainerBuilder();

        $container->register('example', 'Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassDefault')
          ->addTag('auto_alias', array('format' => '%existing%.example'));
        $container->setParameter('existing', 'mysql');

        $pass = new AutoAliasServicePass();
        $pass->process($container);

        $this->assertEquals('Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassDefault', $container->getDefinition('example')->getClass());
        $this->assertInstanceOf('Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassDefault', $container->get('example'));
    }

    public function testProcessWithExistingAlias()
    {
        $container = new ContainerBuilder();

        $container->register('example', 'Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassDefault')
          ->addTag('auto_alias', array('format' => '%existing%.example'));

        $container->register('mysql.example', 'Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassMysql');
        $container->setParameter('existing', 'mysql');

        $pass = new AutoAliasServicePass();
        $pass->process($container);

        $this->assertTrue($container->hasAlias('example'));
        $this->assertEquals('mysql.example', $container->getAlias('example'));
        $this->assertInstanceOf('Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassMysql', $container->get('example'));
    }

    public function testProcessWithManualAlias()
    {
        $container = new ContainerBuilder();

        $container->register('example', 'Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassDefault')
          ->addTag('auto_alias', array('format' => '%existing%.example'));

        $container->register('mysql.example', 'Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassMysql');
        $container->register('mariadb.example', 'Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassMariadb');
        $container->setAlias('example', 'mariadb.example');
        $container->setParameter('existing', 'mysql');

        $pass = new AutoAliasServicePass();
        $pass->process($container);

        $this->assertTrue($container->hasAlias('example'));
        $this->assertEquals('mariadb.example', $container->getAlias('example'));
        $this->assertInstanceOf('Makhan\Component\DependencyInjection\Tests\Compiler\ServiceClassMariaDb', $container->get('example'));
    }
}

class ServiceClassDefault
{
}

class ServiceClassMysql extends ServiceClassDefault
{
}

class ServiceClassMariaDb extends ServiceClassMysql
{
}
