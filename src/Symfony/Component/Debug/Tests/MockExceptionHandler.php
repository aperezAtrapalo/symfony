<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Debug\Tests;

use Makhan\Component\Debug\ExceptionHandler;

class MockExceptionHandler extends Exceptionhandler
{
    public $e;

    public function handle(\Exception $e)
    {
        $this->e = $e;
    }
}
