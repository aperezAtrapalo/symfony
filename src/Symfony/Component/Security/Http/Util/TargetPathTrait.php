<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Util;

use Makhan\Component\HttpFoundation\Session\SessionInterface;

/**
 * Trait to get (and set) the URL the user last visited before being forced to authenticate.
 */
trait TargetPathTrait
{
    /**
     * Sets the target path the user should be redirected to after authentication.
     *
     * Usually, you do not need to set this directly.
     *
     * @param SessionInterface $session
     * @param string           $providerKey The name of your firewall
     * @param string           $uri         The URI to set as the target path
     */
    private function saveTargetPath(SessionInterface $session, $providerKey, $uri)
    {
        $session->set('_security.'.$providerKey.'.target_path', $uri);
    }

    /**
     * Returns the URL (if any) the user visited that forced them to login.
     *
     * @param SessionInterface $session
     * @param string           $providerKey The name of your firewall
     *
     * @return string
     */
    private function getTargetPath(SessionInterface $session, $providerKey)
    {
        return $session->get('_security.'.$providerKey.'.target_path');
    }

    /**
     * Removes the target path from the session.
     *
     * @param SessionInterface $session
     * @param string           $providerKey The name of your firewall
     */
    private function removeTargetPath(SessionInterface $session, $providerKey)
    {
        $session->remove('_security.'.$providerKey.'.target_path');
    }
}
