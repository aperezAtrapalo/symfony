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

use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Makhan\Component\Filesystem\Exception\IOException;
use Makhan\Component\Filesystem\Filesystem;

class CompilerDebugDumpPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $filename = self::getCompilerLogFilename($container);

        $filesystem = new Filesystem();
        $filesystem->dumpFile($filename, implode("\n", $container->getCompiler()->getLog()), null);
        try {
            $filesystem->chmod($filename, 0666, umask());
        } catch (IOException $e) {
            // discard chmod failure (some filesystem may not support it)
        }
    }

    public static function getCompilerLogFilename(ContainerInterface $container)
    {
        $class = $container->getParameter('kernel.container_class');

        return $container->getParameter('kernel.cache_dir').'/'.$class.'Compiler.log';
    }
}
