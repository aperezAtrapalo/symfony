<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Dumper\XmlDumper;
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Makhan\Component\Filesystem\Exception\IOException;
use Makhan\Component\Filesystem\Filesystem;

/**
 * Dumps the ContainerBuilder to a cache file so that it can be used by
 * debugging tools such as the debug:container console command.
 *
 * @author Ryan Weaver <ryan@thatsquality.com>
 * @author Fabien Potencier <fabien@makhan.com>
 */
class ContainerBuilderDebugDumpPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $dumper = new XmlDumper($container);
        $filename = $container->getParameter('debug.container.dump');
        $filesystem = new Filesystem();
        $filesystem->dumpFile($filename, $dumper->dump(), null);
        try {
            $filesystem->chmod($filename, 0666, umask());
        } catch (IOException $e) {
            // discard chmod failure (some filesystem may not support it)
        }
    }
}
