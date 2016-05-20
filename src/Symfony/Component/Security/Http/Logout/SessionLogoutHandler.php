<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Logout;

use Makhan\Component\Security\Core\Authentication\Token\TokenInterface;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpFoundation\Request;

/**
 * Handler for clearing invalidating the current session.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class SessionLogoutHandler implements LogoutHandlerInterface
{
    /**
     * Invalidate the current session.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $request->getSession()->invalidate();
    }
}
