<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\DependencyInjection\Compiler;

use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddConsoleCommandPass;
use Makhan\Component\Console\Command\Command;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Definition;
use Makhan\Component\HttpKernel\Bundle\Bundle;

class AddConsoleCommandPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider visibilityProvider
     */
    public function testProcess($public)
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new AddConsoleCommandPass());
        $container->setParameter('my-command.class', 'Makhan\Bundle\FrameworkBundle\Tests\DependencyInjection\Compiler\MyCommand');

        $definition = new Definition('%my-command.class%');
        $definition->setPublic($public);
        $definition->addTag('console.command');
        $container->setDefinition('my-command', $definition);

        $container->compile();

        $alias = 'console.command.makhan_bundle_frameworkbundle_tests_dependencyinjection_compiler_mycommand';
        if ($container->hasAlias($alias)) {
            $this->assertSame('my-command', (string) $container->getAlias($alias));
        } else {
            // The alias is replaced by a Definition by the ReplaceAliasByActualDefinitionPass
            // in case the original service is private
            $this->assertFalse($container->hasDefinition('my-command'));
            $this->assertTrue($container->hasDefinition($alias));
        }

        $this->assertTrue($container->hasParameter('console.command.ids'));
        $this->assertSame(array('console.command.makhan_bundle_frameworkbundle_tests_dependencyinjection_compiler_mycommand'), $container->getParameter('console.command.ids'));
    }

    public function visibilityProvider()
    {
        return array(
            array(true),
            array(false),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "my-command" tagged "console.command" must not be abstract.
     */
    public function testProcessThrowAnExceptionIfTheServiceIsAbstract()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new AddConsoleCommandPass());

        $definition = new Definition('Makhan\Bundle\FrameworkBundle\Tests\DependencyInjection\Compiler\MyCommand');
        $definition->addTag('console.command');
        $definition->setAbstract(true);
        $container->setDefinition('my-command', $definition);

        $container->compile();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "my-command" tagged "console.command" must be a subclass of "Makhan\Component\Console\Command\Command".
     */
    public function testProcessThrowAnExceptionIfTheServiceIsNotASubclassOfCommand()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new AddConsoleCommandPass());

        $definition = new Definition('SplObjectStorage');
        $definition->addTag('console.command');
        $container->setDefinition('my-command', $definition);

        $container->compile();
    }

    public function testHttpKernelRegisterCommandsIngoreCommandAsAService()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new AddConsoleCommandPass());
        $definition = new Definition('Makhan\Bundle\FrameworkBundle\Tests\DependencyInjection\Compiler\MyCommand');
        $definition->addTag('console.command');
        $container->setDefinition('my-command', $definition);
        $container->compile();

        $application = $this->getMock('Makhan\Component\Console\Application');
        // Never called, because it's the
        // Makhan\Bundle\FrameworkBundle\Console\Application that register
        // commands as a service
        $application->expects($this->never())->method('add');

        $bundle = new ExtensionPresentBundle();
        $bundle->setContainer($container);
        $bundle->registerCommands($application);
    }
}

class MyCommand extends Command
{
}

class ExtensionPresentBundle extends Bundle
{
}
