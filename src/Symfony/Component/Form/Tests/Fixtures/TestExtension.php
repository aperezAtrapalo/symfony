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

use Makhan\Component\Form\FormTypeInterface;
use Makhan\Component\Form\FormTypeExtensionInterface;
use Makhan\Component\Form\FormTypeGuesserInterface;
use Makhan\Component\Form\FormExtensionInterface;

class TestExtension implements FormExtensionInterface
{
    private $types = array();

    private $extensions = array();

    private $guesser;

    public function __construct(FormTypeGuesserInterface $guesser)
    {
        $this->guesser = $guesser;
    }

    public function addType(FormTypeInterface $type)
    {
        $this->types[get_class($type)] = $type;
    }

    public function getType($name)
    {
        return isset($this->types[$name]) ? $this->types[$name] : null;
    }

    public function hasType($name)
    {
        return isset($this->types[$name]);
    }

    public function addTypeExtension(FormTypeExtensionInterface $extension)
    {
        $type = $extension->getExtendedType();

        if (!isset($this->extensions[$type])) {
            $this->extensions[$type] = array();
        }

        $this->extensions[$type][] = $extension;
    }

    public function getTypeExtensions($name)
    {
        return isset($this->extensions[$name]) ? $this->extensions[$name] : array();
    }

    public function hasTypeExtensions($name)
    {
        return isset($this->extensions[$name]);
    }

    public function getTypeGuesser()
    {
        return $this->guesser;
    }
}
