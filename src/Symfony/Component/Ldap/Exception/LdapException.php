<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Ldap\Exception;

/**
 * LdapException is throw if php ldap module is not loaded.
 *
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class LdapException extends \RuntimeException implements ExceptionInterface
{
}
