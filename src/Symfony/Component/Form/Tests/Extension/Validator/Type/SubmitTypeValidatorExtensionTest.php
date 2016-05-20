<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Validator\Type;

class SubmitTypeValidatorExtensionTest extends BaseValidatorExtensionTest
{
    protected function createForm(array $options = array())
    {
        return $this->factory->create('Makhan\Component\Form\Extension\Core\Type\SubmitType', null, $options);
    }
}
