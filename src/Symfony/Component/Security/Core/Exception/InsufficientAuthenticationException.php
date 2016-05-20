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
 * InsufficientAuthenticationException is thrown if the user credentials are not sufficiently trusted.
 *
 * This is the case when a user is anonymous and the resource to be displayed has an access role.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Alexander <iam.asm89@gmail.com>
 */
class InsufficientAuthenticationException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Not privileged to request the resource.';
    }
}
