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
 * Interface that needs to be implemented by LogoutHandlers.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface LogoutHandlerInterface
{
    /**
     * This method is called by the LogoutListener when a user has requested
     * to be logged out. Usually, you would unset session variables, or remove
     * cookies, etc.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token);
}
