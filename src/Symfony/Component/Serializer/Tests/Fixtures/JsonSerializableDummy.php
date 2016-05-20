<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Tests\Fixtures;

class JsonSerializableDummy implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return array(
            'foo' => 'a',
            'bar' => 'b',
            'baz' => 'c',
            'qux' => $this,
        );
    }
}
