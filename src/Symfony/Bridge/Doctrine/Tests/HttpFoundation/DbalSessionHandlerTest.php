<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Tests\HttpFoundation;

use Makhan\Bridge\Doctrine\HttpFoundation\DbalSessionHandler;

/**
 * Test class for DbalSessionHandler.
 *
 * @author Drak <drak@zikula.org>
 */
class DbalSessionHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        $handler = new DbalSessionHandler($connection);
    }
}
