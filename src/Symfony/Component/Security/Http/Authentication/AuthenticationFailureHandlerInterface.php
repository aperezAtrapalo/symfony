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

use Makhan\Component\Security\Core\Exception\AuthenticationException;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;

/**
 * Interface for custom authentication failure handlers.
 *
 * If you want to customize the failure handling process, instead of
 * overwriting the respective listener globally, you can set a custom failure
 * handler which implements this interface.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface AuthenticationFailureHandlerInterface
{
    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception);
}
