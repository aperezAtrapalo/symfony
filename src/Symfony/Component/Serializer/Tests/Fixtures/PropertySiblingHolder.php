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

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PropertySiblingHolder
{
    public $sibling0;
    public $sibling1;
    public $sibling2;

    public function __construct()
    {
        $sibling = new PropertySibling();

        $this->sibling0 = $sibling;
        $this->sibling1 = $sibling;
        $this->sibling2 = $sibling;
    }
}

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PropertySibling
{
    public $coopTilleuls = 'Les-Tilleuls.coop';
}
