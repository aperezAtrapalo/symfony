<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Firewall;

use Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Psr\Log\LoggerInterface;
use Makhan\Component\HttpKernel\Event\GetResponseEvent;
use Makhan\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Makhan\Component\Security\Core\Exception\AuthenticationException;

/**
 * BasicAuthenticationListener implements Basic HTTP authentication.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class BasicAuthenticationListener implements ListenerInterface
{
    private $tokenStorage;
    private $authenticationManager;
    private $providerKey;
    private $authenticationEntryPoint;
    private $logger;
    private $ignoreFailure;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, $providerKey, AuthenticationEntryPointInterface $authenticationEntryPoint, LoggerInterface $logger = null)
    {
        if (empty($providerKey)) {
            throw new \InvalidArgumentException('$providerKey must not be empty.');
        }

        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->providerKey = $providerKey;
        $this->authenticationEntryPoint = $authenticationEntryPoint;
        $this->logger = $logger;
        $this->ignoreFailure = false;
    }

    /**
     * Handles basic authentication.
     *
     * @param GetResponseEvent $event A GetResponseEvent instance
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (false === $username = $request->headers->get('PHP_AUTH_USER', false)) {
            return;
        }

        if (null !== $token = $this->tokenStorage->getToken()) {
            if ($token instanceof UsernamePasswordToken && $token->isAuthenticated() && $token->getUsername() === $username) {
                return;
            }
        }

        if (null !== $this->logger) {
            $this->logger->info('Basic authentication Authorization header found for user.', array('username' => $username));
        }

        try {
            $token = $this->authenticationManager->authenticate(new UsernamePasswordToken($username, $request->headers->get('PHP_AUTH_PW'), $this->providerKey));
            $this->tokenStorage->setToken($token);
        } catch (AuthenticationException $e) {
            $token = $this->tokenStorage->getToken();
            if ($token instanceof UsernamePasswordToken && $this->providerKey === $token->getProviderKey()) {
                $this->tokenStorage->setToken(null);
            }

            if (null !== $this->logger) {
                $this->logger->info('Basic authentication failed for user.', array('username' => $username, 'exception' => $e));
            }

            if ($this->ignoreFailure) {
                return;
            }

            $event->setResponse($this->authenticationEntryPoint->start($request, $e));
        }
    }
}
