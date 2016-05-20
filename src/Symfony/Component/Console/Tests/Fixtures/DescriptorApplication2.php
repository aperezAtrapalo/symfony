<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console\Tests\Fixtures;

use Makhan\Component\Console\Application;

class DescriptorApplication2 extends Application
{
    public function __construct()
    {
        parent::__construct('My Makhan application', 'v1.0');
        $this->add(new DescriptorCommand1());
        $this->add(new DescriptorCommand2());
    }
}
