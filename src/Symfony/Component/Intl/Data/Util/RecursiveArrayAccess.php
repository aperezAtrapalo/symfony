<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Data\Util;

use Makhan\Component\Intl\Exception\OutOfBoundsException;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @internal
 */
class RecursiveArrayAccess
{
    public static function get($array, array $indices)
    {
        foreach ($indices as $index) {
            // Use array_key_exists() for arrays, isset() otherwise
            if (is_array($array)) {
                if (array_key_exists($index, $array)) {
                    $array = $array[$index];
                    continue;
                }
            } elseif ($array instanceof \ArrayAccess) {
                if (isset($array[$index])) {
                    $array = $array[$index];
                    continue;
                }
            }

            throw new OutOfBoundsException(sprintf(
                'The index %s does not exist.',
                $index
            ));
        }

        return $array;
    }

    private function __construct()
    {
    }
}
