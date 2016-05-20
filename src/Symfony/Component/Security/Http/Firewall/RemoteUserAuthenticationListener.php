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
use Psr\Log\LoggerInterface;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\Security\Core\Exception\BadCredentialsException;
use Makhan\Component\EventDispatcher\EventDispatcherInterface;

/**
 * REMOTE_USER authentication listener.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Maxime Douailin <maxime.douailin@gmail.com>
 */
class RemoteUserAuthenticationListener extends AbstractPreAuthenticatedListener
{
    private $userKey;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, $providerKey, $userKey = 'REMOTE_USER', LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct($tokenStorage, $authenticationManager, $providerKey, $logger, $dispatcher);

        $this->userKey = $userKey;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPreAuthenticatedData(Request $request)
    {
        if (!$request->server->has($this->userKey)) {
            throw new BadCredentialsException(sprintf('User key was not found: %s', $this->userKey));
        }

        return array($request->server->get($this->userKey), null);
    }
}
