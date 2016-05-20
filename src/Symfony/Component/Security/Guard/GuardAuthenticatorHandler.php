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

use Makhan\Component\EventDispatcher\EventDispatcherInterface;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Makhan\Component\Security\Core\Authentication\Token\TokenInterface;
use Makhan\Component\Security\Core\Exception\AuthenticationException;
use Makhan\Component\Security\Core\User\UserInterface;
use Makhan\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Makhan\Component\Security\Http\Event\InteractiveLoginEvent;
use Makhan\Component\Security\Http\SecurityEvents;

/**
 * A utility class that does much of the *work* during the guard authentication process.
 *
 * By having the logic here instead of the listener, more of the process
 * can be called directly (e.g. for manual authentication) or overridden.
 *
 * @author Ryan Weaver <ryan@knpuniversity.com>
 */
class GuardAuthenticatorHandler
{
    private $tokenStorage;

    private $dispatcher;

    public function __construct(TokenStorageInterface $tokenStorage, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->dispatcher = $eventDispatcher;
    }

    /**
     * Authenticates the given token in the system.
     *
     * @param TokenInterface $token
     * @param Request        $request
     */
    public function authenticateWithToken(TokenInterface $token, Request $request)
    {
        $this->tokenStorage->setToken($token);

        if (null !== $this->dispatcher) {
            $loginEvent = new InteractiveLoginEvent($request, $token);
            $this->dispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $loginEvent);
        }
    }

    /**
     * Returns the "on success" response for the given GuardAuthenticator.
     *
     * @param TokenInterface              $token
     * @param Request                     $request
     * @param GuardAuthenticatorInterface $guardAuthenticator
     * @param string                      $providerKey        The provider (i.e. firewall) key
     *
     * @return null|Response
     */
    public function handleAuthenticationSuccess(TokenInterface $token, Request $request, GuardAuthenticatorInterface $guardAuthenticator, $providerKey)
    {
        $response = $guardAuthenticator->onAuthenticationSuccess($request, $token, $providerKey);

        // check that it's a Response or null
        if ($response instanceof Response || null === $response) {
            return $response;
        }

        throw new \UnexpectedValueException(sprintf(
            'The %s::onAuthenticationSuccess method must return null or a Response object. You returned %s.',
            get_class($guardAuthenticator),
            is_object($response) ? get_class($response) : gettype($response)
        ));
    }

    /**
     * Convenience method for authenticating the user and returning the
     * Response *if any* for success.
     *
     * @param UserInterface               $user
     * @param Request                     $request
     * @param GuardAuthenticatorInterface $authenticator
     * @param string                      $providerKey   The provider (i.e. firewall) key
     *
     * @return Response|null
     */
    public function authenticateUserAndHandleSuccess(UserInterface $user, Request $request, GuardAuthenticatorInterface $authenticator, $providerKey)
    {
        // create an authenticated token for the User
        $token = $authenticator->createAuthenticatedToken($user, $providerKey);
        // authenticate this in the system
        $this->authenticateWithToken($token, $request);

        // return the success metric
        return $this->handleAuthenticationSuccess($token, $request, $authenticator, $providerKey);
    }

    /**
     * Handles an authentication failure and returns the Response for the
     * GuardAuthenticator.
     *
     * @param AuthenticationException     $authenticationException
     * @param Request                     $request
     * @param GuardAuthenticatorInterface $guardAuthenticator
     * @param string                      $providerKey             The key of the firewall
     *
     * @return null|Response
     */
    public function handleAuthenticationFailure(AuthenticationException $authenticationException, Request $request, GuardAuthenticatorInterface $guardAuthenticator, $providerKey)
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof PostAuthenticationGuardToken && $providerKey === $token->getProviderKey()) {
            $this->tokenStorage->setToken(null);
        }

        $response = $guardAuthenticator->onAuthenticationFailure($request, $authenticationException);
        if ($response instanceof Response || null === $response) {
            // returning null is ok, it means they want the request to continue
            return $response;
        }

        throw new \UnexpectedValueException(sprintf(
            'The %s::onAuthenticationFailure method must return null or a Response object. You returned %s.',
            get_class($guardAuthenticator),
            is_object($response) ? get_class($response) : gettype($response)
        ));
    }
}
