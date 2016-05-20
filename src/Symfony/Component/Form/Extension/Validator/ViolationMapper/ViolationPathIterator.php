<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Validator\ViolationMapper;

use Makhan\Component\PropertyAccess\PropertyPathIterator;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ViolationPathIterator extends PropertyPathIterator
{
    public function __construct(ViolationPath $violationPath)
    {
        parent::__construct($violationPath);
    }

    public function mapsForm()
    {
        return $this->path->mapsForm($this->key());
    }
}
