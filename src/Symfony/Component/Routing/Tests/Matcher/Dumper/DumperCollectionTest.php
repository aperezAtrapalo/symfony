<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Routing\Tests\Matcher\Dumper;

use Makhan\Component\Routing\Matcher\Dumper\DumperCollection;

class DumperCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRoot()
    {
        $a = new DumperCollection();

        $b = new DumperCollection();
        $a->add($b);

        $c = new DumperCollection();
        $b->add($c);

        $d = new DumperCollection();
        $c->add($d);

        $this->assertSame($a, $c->getRoot());
    }
}
