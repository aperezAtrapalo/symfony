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
 * @author Stepan Anchugov <kixxx1@gmail.com>
 */
class BirthdayTypeTest extends BaseTypeTest
{
    /**
     * @expectedException \Makhan\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testSetInvalidYearsOption()
    {
        $this->factory->create('Makhan\Component\Form\Extension\Core\Type\BirthdayType', null, array(
            'years' => 'bad value',
        ));
    }

    protected function getTestedType()
    {
        return 'Makhan\Component\Form\Extension\Core\Type\BirthdayType';
    }
}
