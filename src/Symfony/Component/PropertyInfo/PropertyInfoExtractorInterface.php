<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\PropertyInfo;

/**
 * Gets info about PHP class properties.
 *
 * A convenient interface inheriting all specific info interfaces.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface PropertyInfoExtractorInterface extends PropertyTypeExtractorInterface, PropertyDescriptionExtractorInterface, PropertyAccessExtractorInterface, PropertyListExtractorInterface
{
}
