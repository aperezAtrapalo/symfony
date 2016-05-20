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

use Makhan\Component\Form\Tests\Fixtures\CustomArrayObject;
use Makhan\Component\Form\FormBuilder;

class MergeCollectionListenerCustomArrayObjectTest extends MergeCollectionListenerTest
{
    protected function getData(array $data)
    {
        return new CustomArrayObject($data);
    }

    protected function getBuilder($name = 'name')
    {
        return new FormBuilder($name, 'Makhan\Component\Form\Tests\Fixtures\CustomArrayObject', $this->dispatcher, $this->factory);
    }
}
