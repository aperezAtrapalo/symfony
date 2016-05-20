<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Fixtures;

use Makhan\Component\Form\AbstractTypeExtension;
use Makhan\Component\Form\FormBuilderInterface;

class FooTypeBazExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('baz', 'x');
    }

    public function getExtendedType()
    {
        return __NAMESPACE__.'\FooType';
    }
}
