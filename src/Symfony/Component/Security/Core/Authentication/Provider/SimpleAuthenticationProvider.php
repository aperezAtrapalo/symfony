<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Authentication\Provider;

use Makhan\Component\Security\Core\User\UserProviderInterface;
use Makhan\Component\Security\Core\Authentication\Token\TokenInterface;
use Makhan\Component\Security\Core\Authentication\SimpleAuthenticatorInterface;
use Makhan\Component\Security\Core\Exception\AuthenticationException;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class SimpleAuthenticationProvider implements AuthenticationProviderInterface
{
    private $simpleAuthenticator;
    private $userProvider;
    private $providerKey;

    public function __construct(SimpleAuthenticatorInterface $simpleAuthenticator, UserProviderInterface $userProvider, $providerKey)
    {
        $this->simpleAuthenticator = $simpleAuthenticator;
        $this->userProvider = $userProvider;
        $this->providerKey = $providerKey;
    }

    public function authenticate(TokenInterface $token)
    {
        $authToken = $this->simpleAuthenticator->authenticateToken($token, $this->userProvider, $this->providerKey);

        if ($authToken instanceof TokenInterface) {
            return $authToken;
        }

        throw new AuthenticationException('Simple authenticator failed to return an authenticated token.');
    }

    public function supports(TokenInterface $token)
    {
        return $this->simpleAuthenticator->supportsToken($token, $this->providerKey);
    }
}
