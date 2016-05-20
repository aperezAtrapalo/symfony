<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Exception;

/**
 * AuthenticationServiceException is thrown when an authenticated token becomes un-authentcated between requests.
 *
 * In practice, this is due to the User changing between requests (e.g. password changes),
 * causes the token to become un-authenticated.
 *
 * @author Ryan Weaver <ryan@knpuniversity.com>
 */
class AuthenticationExpiredException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Authentication expired because your account information has changed.';
    }
}
