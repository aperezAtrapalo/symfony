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

use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\Security\Core\Exception\AuthenticationException;

/**
 * @author Fabien Potencier <fabien@makhan.com>
 */
class CustomAuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    private $handler;

    /**
     * Constructor.
     *
     * @param AuthenticationFailureHandlerInterface $handler An AuthenticationFailureHandlerInterface instance
     * @param array                                 $options Options for processing a successful authentication attempt
     */
    public function __construct(AuthenticationFailureHandlerInterface $handler, array $options)
    {
        $this->handler = $handler;
        if (method_exists($handler, 'setOptions')) {
            $this->handler->setOptions($options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $this->handler->onAuthenticationFailure($request, $exception);
    }
}
