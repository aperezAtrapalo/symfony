<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Translation\Exception;

/**
 * Thrown when a resource does not exist.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class NotFoundResourceException extends \InvalidArgumentException implements ExceptionInterface
{
}
