<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console\Helper;

/**
 * Marks a row as being a separator.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class TableSeparator extends TableCell
{
    /**
     * @param string $value
     * @param array  $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct('', $options);
    }
}
