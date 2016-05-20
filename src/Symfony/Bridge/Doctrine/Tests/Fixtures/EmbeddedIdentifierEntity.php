<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class EmbeddedIdentifierEntity
{
    /**
     * @var Embeddable\Identifier
     *
     * @ORM\Embedded(class="Makhan\Bridge\Doctrine\Tests\Fixtures\Embeddable\Identifier")
     */
    protected $id;
}
