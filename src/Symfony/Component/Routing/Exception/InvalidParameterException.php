<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Routing\Exception;

/**
 * Exception thrown when a parameter is not valid.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class InvalidParameterException extends \InvalidArgumentException implements ExceptionInterface
{
}
