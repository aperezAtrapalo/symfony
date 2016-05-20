<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\PropertyAccess\Tests\Fixtures;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class TypeHinted
{
    private $date;

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }
}
