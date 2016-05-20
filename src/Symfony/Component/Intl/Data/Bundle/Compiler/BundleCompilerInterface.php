<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Data\Bundle\Compiler;

/**
 * Compiles a resource bundle.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @internal
 */
interface BundleCompilerInterface
{
    /**
     * Compiles a resource bundle at the given source to the given target
     * directory.
     *
     * @param string $sourcePath
     * @param string $targetDir
     */
    public function compile($sourcePath, $targetDir);
}
