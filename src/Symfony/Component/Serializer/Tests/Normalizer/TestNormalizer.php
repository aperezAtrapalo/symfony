<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Tests\Normalizer;

use Makhan\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Provides a test Normalizer which only implements the NormalizerInterface.
 *
 * @author Lin Clark <lin@lin-clark.com>
 */
class TestNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return true;
    }
}
