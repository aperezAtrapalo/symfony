<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Finder\Exception;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface ExceptionInterface
{
    /**
     * @return \Makhan\Component\Finder\Adapter\AdapterInterface
     */
    public function getAdapter();
}
