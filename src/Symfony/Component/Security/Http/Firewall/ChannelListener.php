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

use Makhan\Component\Security\Http\AccessMapInterface;
use Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Psr\Log\LoggerInterface;
use Makhan\Component\HttpKernel\Event\GetResponseEvent;

/**
 * ChannelListener switches the HTTP protocol based on the access control
 * configuration.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class ChannelListener implements ListenerInterface
{
    private $map;
    private $authenticationEntryPoint;
    private $logger;

    public function __construct(AccessMapInterface $map, AuthenticationEntryPointInterface $authenticationEntryPoint, LoggerInterface $logger = null)
    {
        $this->map = $map;
        $this->authenticationEntryPoint = $authenticationEntryPoint;
        $this->logger = $logger;
    }

    /**
     * Handles channel management.
     *
     * @param GetResponseEvent $event A GetResponseEvent instance
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        list(, $channel) = $this->map->getPatterns($request);

        if ('https' === $channel && !$request->isSecure()) {
            if (null !== $this->logger) {
                $this->logger->info('Redirecting to HTTPS.');
            }

            $response = $this->authenticationEntryPoint->start($request);

            $event->setResponse($response);

            return;
        }

        if ('http' === $channel && $request->isSecure()) {
            if (null !== $this->logger) {
                $this->logger->info('Redirecting to HTTP.');
            }

            $response = $this->authenticationEntryPoint->start($request);

            $event->setResponse($response);
        }
    }
}
