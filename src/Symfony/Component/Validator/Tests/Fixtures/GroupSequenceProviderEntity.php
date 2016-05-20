<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Fixtures;

use Makhan\Component\Validator\Constraints as Assert;
use Makhan\Component\Validator\GroupSequenceProviderInterface;

/**
 * @Assert\GroupSequenceProvider
 */
class GroupSequenceProviderEntity implements GroupSequenceProviderInterface
{
    public $firstName;
    public $lastName;

    protected $sequence = array();

    public function __construct($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getGroupSequence()
    {
        return $this->sequence;
    }
}
