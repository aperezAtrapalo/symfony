<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\VarDumper\Caster;

use Makhan\Component\VarDumper\Cloner\Stub;

/**
 * Casts classes from the MongoDb extension to array representation.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class MongoCaster
{
    public static function castCursor(\MongoCursorInterface $cursor, array $a, Stub $stub, $isNested)
    {
        if ($info = $cursor->info()) {
            foreach ($info as $k => $v) {
                $a[Caster::PREFIX_VIRTUAL.$k] = $v;
            }
        }
        $a[Caster::PREFIX_VIRTUAL.'dead'] = $cursor->dead();

        return $a;
    }
}
