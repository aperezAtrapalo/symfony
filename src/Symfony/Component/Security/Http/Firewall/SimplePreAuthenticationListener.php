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
use Psr\Log\LoggerInterface;
use Makhan\Component\HttpKernel\Event\GetResponseEvent;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Makhan\Component\Security\Core\Authentication\Token\AnonymousToken;
use Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Makhan\Component\Security\Core\Exception\AuthenticationException;
use Makhan\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Makhan\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Makhan\Component\Security\Http\Event\InteractiveLoginEvent;
use Makhan\Component\Security\Http\SecurityEvents;
use Makhan\Component\EventDispatcher\EventDispatcherInterface;

/**
 * SimplePreAuthenticationListener implements simple proxying to an authenticator.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class SimplePreAuthenticationListener implements ListenerInterface
{
    private $tokenStorage;
    private $authenticationManager;
    private $providerKey;
    private $simpleAuthenticator;
    private $logger;
    private $dispatcher;

    /**
     * Constructor.
     *
     * @param TokenStorageInterface           $tokenStorage          A TokenStorageInterface instance
     * @param AuthenticationManagerInterface  $authenticationManager An AuthenticationManagerInterface instance
     * @param string                          $providerKey
     * @param SimplePreAuthenticatorInterface $simpleAuthenticator   A SimplePreAuthenticatorInterface instance
     * @param LoggerInterface                 $logger                A LoggerInterface instance
     * @param EventDispatcherInterface        $dispatcher            An EventDispatcherInterface instance
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, $providerKey, SimplePreAuthenticatorInterface $simpleAuthenticator, LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null)
    {
        if (empty($providerKey)) {
            throw new \InvalidArgumentException('$providerKey must not be empty.');
        }

        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->providerKey = $providerKey;
        $this->simpleAuthenticator = $simpleAuthenticator;
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handles basic authentication.
     *
     * @param GetResponseEvent $event A GetResponseEvent instance
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (null !== $this->logger) {
            $this->logger->info('Attempting SimplePreAuthentication.', array('key' => $this->providerKey, 'authenticator' => get_class($this->simpleAuthenticator)));
        }

        if (null !== $this->tokenStorage->getToken() && !$this->tokenStorage->getToken() instanceof AnonymousToken) {
            return;
        }

        try {
            $token = $this->simpleAuthenticator->createToken($request, $this->providerKey);

            // allow null to be returned to skip authentication
            if (null === $token) {
                return;
            }

            $token = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($token);

            if (null !== $this->dispatcher) {
                $loginEvent = new InteractiveLoginEvent($request, $token);
                $this->dispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $loginEvent);
            }
        } catch (AuthenticationException $e) {
            $this->tokenStorage->setToken(null);

            if (null !== $this->logger) {
                $this->logger->info('SimplePreAuthentication request failed.', array('exception' => $e, 'authenticator' => get_class($this->simpleAuthenticator)));
            }

            if ($this->simpleAuthenticator instanceof AuthenticationFailureHandlerInterface) {
                $response = $this->simpleAuthenticator->onAuthenticationFailure($request, $e);
                if ($response instanceof Response) {
                    $event->setResponse($response);
                } elseif (null !== $response) {
                    throw new \UnexpectedValueException(sprintf('The %s::onAuthenticationFailure method must return null or a Response object', get_class($this->simpleAuthenticator)));
                }
            }

            return;
        }

        if ($this->simpleAuthenticator instanceof AuthenticationSuccessHandlerInterface) {
            $response = $this->simpleAuthenticator->onAuthenticationSuccess($request, $token);
            if ($response instanceof Response) {
                $event->setResponse($response);
            } elseif (null !== $response) {
                throw new \UnexpectedValueException(sprintf('The %s::onAuthenticationSuccess method must return null or a Response object', get_class($this->simpleAuthenticator)));
            }
        }
    }
}
