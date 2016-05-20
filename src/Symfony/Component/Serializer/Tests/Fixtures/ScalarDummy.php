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

class ScalarDummy implements NormalizableInterface, DenormalizableInterface
{
    public $foo;
    public $xmlFoo;

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = array())
    {
        return $format === 'xml' ? $this->xmlFoo : $this->foo;
    }

    public function denormalize(DenormalizerInterface $denormalizer, $data, $format = null, array $context = array())
    {
        if ($format === 'xml') {
            $this->xmlFoo = $data;
        } else {
            $this->foo = $data;
        }
    }
}
