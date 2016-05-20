<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Normalizer;

use Makhan\Component\Serializer\SerializerAwareTrait;
use Makhan\Component\Serializer\SerializerAwareInterface;

/**
 * SerializerAware Normalizer implementation.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 *
 * @deprecated since version 3.1, to be removed in 4.0. Use the SerializerAwareTrait instead.
 */
abstract class SerializerAwareNormalizer implements SerializerAwareInterface
{
    use SerializerAwareTrait;
}
