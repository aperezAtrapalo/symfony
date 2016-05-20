<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Makhan\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\TestBundle;
use Makhan\Bundle\FrameworkBundle\FrameworkBundle;

return array(
    new FrameworkBundle(),
    new TestBundle(),
);
