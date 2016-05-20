<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Exception;

/**
 * @author Eriksen Costa <eriksen.costa@infranology.com.br>
 */
class MethodArgumentNotImplementedException extends NotImplementedException
{
    /**
     * Constructor.
     *
     * @param string $methodName The method name that raised the exception
     * @param string $argName    The argument name that is not implemented
     */
    public function __construct($methodName, $argName)
    {
        $message = sprintf('The %s() method\'s argument $%s behavior is not implemented.', $methodName, $argName);
        parent::__construct($message);
    }
}
