<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Core\DataTransformer;

use Makhan\Component\Form\Exception\TransformationFailedException;
use Makhan\Component\Form\DataTransformerInterface;
use Makhan\Component\Form\ChoiceList\ChoiceListInterface;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ChoicesToValuesTransformer implements DataTransformerInterface
{
    private $choiceList;

    /**
     * Constructor.
     *
     * @param ChoiceListInterface $choiceList
     */
    public function __construct(ChoiceListInterface $choiceList)
    {
        $this->choiceList = $choiceList;
    }

    /**
     * @param array $array
     *
     * @return array
     *
     * @throws TransformationFailedException If the given value is not an array.
     */
    public function transform($array)
    {
        if (null === $array) {
            return array();
        }

        if (!is_array($array)) {
            throw new TransformationFailedException('Expected an array.');
        }

        return $this->choiceList->getValuesForChoices($array);
    }

    /**
     * @param array $array
     *
     * @return array
     *
     * @throws TransformationFailedException If the given value is not an array
     *                                       or if no matching choice could be
     *                                       found for some given value.
     */
    public function reverseTransform($array)
    {
        if (null === $array) {
            return array();
        }

        if (!is_array($array)) {
            throw new TransformationFailedException('Expected an array.');
        }

        $choices = $this->choiceList->getChoicesForValues($array);

        if (count($choices) !== count($array)) {
            throw new TransformationFailedException('Could not find all matching choices for the given values');
        }

        return $choices;
    }
}
