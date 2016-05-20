<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http;

final class SecurityEvents
{
    /**
     * The INTERACTIVE_LOGIN event occurs after a user is logged in
     * interactively for authentication based on http, cookies or X509.
     *
     * @Event("Makhan\Component\Security\Http\Event\InteractiveLoginEvent")
     *
     * @var string
     */
    const INTERACTIVE_LOGIN = 'security.interactive_login';

    /**
     * The SWITCH_USER event occurs before switch to another user and
     * before exit from an already switched user.
     *
     * @Event("Makhan\Component\Security\Http\Event\SwitchUserEvent")
     *
     * @var string
     */
    const SWITCH_USER = 'security.switch_user';
}
