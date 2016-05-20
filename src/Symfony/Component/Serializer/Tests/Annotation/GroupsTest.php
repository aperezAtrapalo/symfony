<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Tests\Annotation;

use Makhan\Component\Serializer\Annotation\Groups;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class GroupsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Makhan\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testEmptyGroupsParameter()
    {
        new Groups(array('value' => array()));
    }

    /**
     * @expectedException \Makhan\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testNotAnArrayGroupsParameter()
    {
        new Groups(array('value' => 'coopTilleuls'));
    }

    /**
     * @expectedException \Makhan\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testInvalidGroupsParameter()
    {
        new Groups(array('value' => array('a', 1, new \stdClass())));
    }

    public function testGroupsParameters()
    {
        $validData = array('a', 'b');

        $groups = new Groups(array('value' => $validData));
        $this->assertEquals($validData, $groups->getGroups());
    }
}
