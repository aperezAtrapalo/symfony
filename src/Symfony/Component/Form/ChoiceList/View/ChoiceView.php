<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\ChoiceList\View;

/**
 * Represents a choice in templates.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ChoiceView
{
    /**
     * The label displayed to humans.
     *
     * @var string
     */
    public $label;

    /**
     * The view representation of the choice.
     *
     * @var string
     */
    public $value;

    /**
     * The original choice value.
     *
     * @var mixed
     */
    public $data;

    /**
     * Additional attributes for the HTML tag.
     *
     * @var array
     */
    public $attr;

    /**
     * Creates a new choice view.
     *
     * @param mixed  $data  The original choice
     * @param string $value The view representation of the choice
     * @param string $label The label displayed to humans
     * @param array  $attr  Additional attributes for the HTML tag
     */
    public function __construct($data, $value, $label, array $attr = array())
    {
        $this->data = $data;
        $this->value = $value;
        $this->label = $label;
        $this->attr = $attr;
    }
}
