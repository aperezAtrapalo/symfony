<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Tests\PropertyInfo\Fixtures;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DoctrineRelation
{
    /**
     * @Id
     * @Column(type="smallint")
     */
    public $id;

    /**
     * @Column(type="guid")
     */
    protected $guid;
}
