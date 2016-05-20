<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\Fixtures;

use Makhan\Component\EventDispatcher\Debug\TraceableEventDispatcherInterface;
use Makhan\Component\EventDispatcher\EventDispatcher;

class TestEventDispatcher extends EventDispatcher implements TraceableEventDispatcherInterface
{
    public function getCalledListeners()
    {
        return array('foo');
    }

    public function getNotCalledListeners()
    {
        return array('bar');
    }
}
