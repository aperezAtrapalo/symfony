<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Guard;

use Makhan\Component\Security\Core\User\UserInterface;
use Makhan\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * An optional base class that creates a PostAuthenticationGuardToken for you.
 *
 * @author Ryan Weaver <ryan@knpuniversity.com>
 */
abstract class AbstractGuardAuthenticator implements GuardAuthenticatorInterface
{
    /**
     * Shortcut to create a PostAuthenticationGuardToken for you, if you don't really
     * care about which authenticated token you're using.
     *
     * @param UserInterface $user
     * @param string        $providerKey
     *
     * @return PostAuthenticationGuardToken
     */
    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        return new PostAuthenticationGuardToken(
            $user,
            $providerKey,
            $user->getRoles()
        );
    }
}
