<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\DebugBundle\Tests\DependencyInjection\Compiler;

use Makhan\Bundle\DebugBundle\DependencyInjection\Compiler\DumpDataCollectorPass;
use Makhan\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Definition;
use Makhan\Component\HttpFoundation\RequestStack;

class DumpDataCollectorPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessWithFileLinkFormatParameter()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new DumpDataCollectorPass());
        $container->setParameter('templating.helper.code.file_link_format', 'file-link-format');

        $definition = new Definition('Makhan\Component\HttpKernel\DataCollector\DumpDataCollector', array(null, null, null, null));
        $container->setDefinition('data_collector.dump', $definition);

        $container->compile();

        $this->assertSame('file-link-format', $definition->getArgument(1));
    }

    public function testProcessWithoutFileLinkFormatParameter()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new DumpDataCollectorPass());

        $definition = new Definition('Makhan\Component\HttpKernel\DataCollector\DumpDataCollector', array(null, null, null, null));
        $container->setDefinition('data_collector.dump', $definition);

        $container->compile();

        $this->assertNull($definition->getArgument(1));
    }

    public function testProcessWithToolbarEnabled()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new DumpDataCollectorPass());
        $requestStack = new RequestStack();

        $definition = new Definition('Makhan\Component\HttpKernel\DataCollector\DumpDataCollector', array(null, null, null, $requestStack));
        $container->setDefinition('data_collector.dump', $definition);
        $container->setParameter('web_profiler.debug_toolbar.mode', WebDebugToolbarListener::ENABLED);

        $container->compile();

        $this->assertSame($requestStack, $definition->getArgument(3));
    }

    public function testProcessWithToolbarDisabled()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new DumpDataCollectorPass());

        $definition = new Definition('Makhan\Component\HttpKernel\DataCollector\DumpDataCollector', array(null, null, null, new RequestStack()));
        $container->setDefinition('data_collector.dump', $definition);
        $container->setParameter('web_profiler.debug_toolbar.mode', WebDebugToolbarListener::DISABLED);

        $container->compile();

        $this->assertNull($definition->getArgument(3));
    }

    public function testProcessWithoutToolbar()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new DumpDataCollectorPass());

        $definition = new Definition('Makhan\Component\HttpKernel\DataCollector\DumpDataCollector', array(null, null, null, new RequestStack()));
        $container->setDefinition('data_collector.dump', $definition);

        $container->compile();

        $this->assertNull($definition->getArgument(3));
    }
}
