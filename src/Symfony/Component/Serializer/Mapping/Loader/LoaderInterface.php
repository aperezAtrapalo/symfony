<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Mapping\Loader;

use Makhan\Component\Serializer\Mapping\ClassMetadataInterface;

/**
 * Loads {@link ClassMetadataInterface}.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface LoaderInterface
{
    /**
     * Load class metadata.
     *
     * @param ClassMetadataInterface $classMetadata A metadata
     *
     * @return bool
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata);
}
