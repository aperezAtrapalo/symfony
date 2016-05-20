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
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @Entity
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DoctrineDummy
{
    /**
     * @Id
     * @Column(type="smallint")
     */
    public $id;

    /**
     * @ManyToOne(targetEntity="DoctrineRelation")
     */
    public $foo;

    /**
     * @ManyToMany(targetEntity="DoctrineRelation")
     */
    public $bar;

    /**
     * @ManyToMany(targetEntity="DoctrineRelation", indexBy="guid")
     */
    protected $indexedBar;

    /**
     * @Column(type="guid")
     */
    protected $guid;

    /**
     * @Column(type="time")
     */
    private $time;

    /**
     * @Column(type="json_array")
     */
    private $json;

    /**
     * @Column(type="simple_array")
     */
    private $simpleArray;

    /**
     * @Column(type="boolean")
     */
    private $bool;

    /**
     * @Column(type="binary")
     */
    private $binary;

    /**
     * @Column(type="custom_foo")
     */
    private $customFoo;

    public $notMapped;
}
