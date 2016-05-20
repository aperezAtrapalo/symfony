<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Core\Type;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ButtonTypeTest extends BaseTypeTest
{
    public function testCreateButtonInstances()
    {
        $this->assertInstanceOf('Makhan\Component\Form\Button', $this->factory->create('Makhan\Component\Form\Extension\Core\Type\ButtonType'));
    }

    protected function getTestedType()
    {
        return 'Makhan\Component\Form\Extension\Core\Type\ButtonType';
    }
}
