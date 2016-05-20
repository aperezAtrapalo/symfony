<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Event;

use Makhan\Component\Security\Core\Exception\AuthenticationException;
use Makhan\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * This event is dispatched on authentication failure.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class AuthenticationFailureEvent extends AuthenticationEvent
{
    private $authenticationException;

    public function __construct(TokenInterface $token, AuthenticationException $ex)
    {
        parent::__construct($token);

        $this->authenticationException = $ex;
    }

    public function getAuthenticationException()
    {
        return $this->authenticationException;
    }
}
