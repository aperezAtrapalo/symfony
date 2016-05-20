<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Core\EventListener;

use Makhan\Component\Form\FormBuilder;

class MergeCollectionListenerArrayObjectTest extends MergeCollectionListenerTest
{
    protected function getData(array $data)
    {
        return new \ArrayObject($data);
    }

    protected function getBuilder($name = 'name')
    {
        return new FormBuilder($name, '\ArrayObject', $this->dispatcher, $this->factory);
    }
}
