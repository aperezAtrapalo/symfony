<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\User;

use Makhan\Component\Security\Core\Exception\AccountStatusException;

/**
 * Implement to throw AccountStatusException during the authentication process.
 *
 * Can be used when you want to check the account status, e.g when the account is
 * disabled or blocked. This should not be used to make authentication decisions.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
interface UserCheckerInterface
{
    /**
     * Checks the user account before authentication.
     *
     * @param UserInterface $user a UserInterface instance
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user);

    /**
     * Checks the user account after authentication.
     *
     * @param UserInterface $user a UserInterface instance
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user);
}
