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
use Makhan\Component\Security\Core\Authentication\SimpleAuthenticatorInterface;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
interface SimplePreAuthenticatorInterface extends SimpleAuthenticatorInterface
{
    public function createToken(Request $request, $providerKey);
}
