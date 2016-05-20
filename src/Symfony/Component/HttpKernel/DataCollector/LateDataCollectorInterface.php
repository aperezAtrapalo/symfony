<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\DataCollector;

/**
 * LateDataCollectorInterface.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
interface LateDataCollectorInterface
{
    /**
     * Collects data as late as possible.
     */
    public function lateCollect();
}
