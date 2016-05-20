<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Tests\Fixtures;

use Makhan\Component\Serializer\Normalizer\NormalizableInterface;
use Makhan\Component\Serializer\Normalizer\DenormalizableInterface;
use Makhan\Component\Serializer\Normalizer\NormalizerInterface;
use Makhan\Component\Serializer\Normalizer\DenormalizerInterface;

class NormalizableTraversableDummy extends TraversableDummy implements NormalizableInterface, DenormalizableInterface
{
    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = array())
    {
        return array(
            'foo' => 'normalizedFoo',
            'bar' => 'normalizedBar',
        );
    }

    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = null, array $context = array())
    {
        return array(
            'foo' => 'denormalizedFoo',
            'bar' => 'denormalizedBar',
        );
    }
}
