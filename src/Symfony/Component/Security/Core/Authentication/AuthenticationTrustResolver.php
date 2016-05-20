<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Authentication;

use Makhan\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * The default implementation of the authentication trust resolver.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class AuthenticationTrustResolver implements AuthenticationTrustResolverInterface
{
    private $anonymousClass;
    private $rememberMeClass;

    /**
     * Constructor.
     *
     * @param string $anonymousClass
     * @param string $rememberMeClass
     */
    public function __construct($anonymousClass, $rememberMeClass)
    {
        $this->anonymousClass = $anonymousClass;
        $this->rememberMeClass = $rememberMeClass;
    }

    /**
     * {@inheritdoc}
     */
    public function isAnonymous(TokenInterface $token = null)
    {
        if (null === $token) {
            return false;
        }

        return $token instanceof $this->anonymousClass;
    }

    /**
     * {@inheritdoc}
     */
    public function isRememberMe(TokenInterface $token = null)
    {
        if (null === $token) {
            return false;
        }

        return $token instanceof $this->rememberMeClass;
    }

    /**
     * {@inheritdoc}
     */
    public function isFullFledged(TokenInterface $token = null)
    {
        if (null === $token) {
            return false;
        }

        return !$this->isAnonymous($token) && !$this->isRememberMe($token);
    }
}
