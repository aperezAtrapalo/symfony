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

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;

/** @Entity */
class SingleAssociationToIntIdEntity
{
    /** @Id @OneToOne(targetEntity="SingleIntIdNoToStringEntity", cascade={"ALL"}) */
    protected $entity;

    /** @Column(type="string", nullable=true) */
    public $name;

    public function __construct(SingleIntIdNoToStringEntity $entity, $name)
    {
        $this->entity = $entity;
        $this->name = $name;
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
