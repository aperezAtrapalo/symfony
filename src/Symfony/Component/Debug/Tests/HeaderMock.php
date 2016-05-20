<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Debug;

function headers_sent()
{
    return false;
}

function header($str, $replace = true, $status = null)
{
    Tests\testHeader($str, $replace, $status);
}

namespace Makhan\Component\Debug\Tests;

function testHeader()
{
    static $headers = array();

    if (!$h = func_get_args()) {
        $h = $headers;
        $headers = array();

        return $h;
    }

    $headers[] = func_get_args();
}
