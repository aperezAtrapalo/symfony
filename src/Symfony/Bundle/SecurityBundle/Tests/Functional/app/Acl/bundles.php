<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return array(
    new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
    new Makhan\Bundle\SecurityBundle\SecurityBundle(),
    new Makhan\Bundle\FrameworkBundle\FrameworkBundle(),
    new Makhan\Bundle\SecurityBundle\Tests\Functional\Bundle\AclBundle\AclBundle(),
);
