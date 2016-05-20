<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Tests\Globals;

use Makhan\Component\Intl\Globals\IntlGlobals;

class IntlGlobalsTest extends AbstractIntlGlobalsTest
{
    protected function getIntlErrorName($errorCode)
    {
        return IntlGlobals::getErrorName($errorCode);
    }
}
