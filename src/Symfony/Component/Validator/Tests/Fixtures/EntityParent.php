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

use Makhan\Component\Validator\Constraints\NotNull;

class EntityParent
{
    protected $firstName;
    private $internal;
    private $data = 'Data';

    /**
     * @NotNull
     */
    protected $other;

    public function getData()
    {
        return 'Data';
    }
}
