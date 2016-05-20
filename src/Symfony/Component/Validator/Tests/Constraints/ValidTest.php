<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Constraints;

use Makhan\Component\Validator\Constraints\Valid;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ValidTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Makhan\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testRejectGroupsOption()
    {
        new Valid(array('groups' => 'foo'));
    }
}
