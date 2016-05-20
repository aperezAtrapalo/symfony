<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Validator\ViolationMapper;

use Makhan\Component\Form\FormInterface;
use Makhan\Component\PropertyAccess\PropertyPath;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RelativePath extends PropertyPath
{
    /**
     * @var FormInterface
     */
    private $root;

    /**
     * @param FormInterface $root
     * @param string        $propertyPath
     */
    public function __construct(FormInterface $root, $propertyPath)
    {
        parent::__construct($propertyPath);

        $this->root = $root;
    }

    /**
     * @return FormInterface
     */
    public function getRoot()
    {
        return $this->root;
    }
}
