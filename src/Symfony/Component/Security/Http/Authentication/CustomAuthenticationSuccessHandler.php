<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Authentication;

use Makhan\Component\Security\Core\Authentication\Token\TokenInterface;
use Makhan\Component\HttpFoundation\Request;

/**
 * @author Fabien Potencier <fabien@makhan.com>
 */
class CustomAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $handler;

    /**
     * Constructor.
     *
     * @param AuthenticationSuccessHandlerInterface $handler     An AuthenticationSuccessHandlerInterface instance
     * @param array                                 $options     Options for processing a successful authentication attempt
     * @param string                                $providerKey The provider key
     */
    public function __construct(AuthenticationSuccessHandlerInterface $handler, array $options, $providerKey)
    {
        $this->handler = $handler;
        if (method_exists($handler, 'setOptions')) {
            $this->handler->setOptions($options);
        }
        if (method_exists($handler, 'setProviderKey')) {
            $this->handler->setProviderKey($providerKey);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return $this->handler->onAuthenticationSuccess($request, $token);
    }
}
