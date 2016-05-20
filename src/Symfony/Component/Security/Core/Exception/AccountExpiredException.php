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
 * AccountExpiredException is thrown when the user account has expired.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Alexander <iam.asm89@gmail.com>
 */
class AccountExpiredException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Account has expired.';
    }
}
