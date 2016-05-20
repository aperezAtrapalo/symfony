<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Encoder;

use Makhan\Component\Serializer\SerializerInterface;
use Makhan\Component\Serializer\SerializerAwareInterface;

/**
 * SerializerAware Encoder implementation.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
abstract class SerializerAwareEncoder implements SerializerAwareInterface
{
    protected $serializer;

    /**
     * {@inheritdoc}
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
