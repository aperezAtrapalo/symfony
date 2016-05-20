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
class MethodNotImplementedException extends NotImplementedException
{
    /**
     * Constructor.
     *
     * @param string $methodName The name of the method
     */
    public function __construct($methodName)
    {
        parent::__construct(sprintf('The %s() is not implemented.', $methodName));
    }
}
