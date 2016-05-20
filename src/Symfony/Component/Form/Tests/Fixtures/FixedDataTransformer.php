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

use Makhan\Component\Form\DataTransformerInterface;
use Makhan\Component\Form\Exception\TransformationFailedException;

class FixedDataTransformer implements DataTransformerInterface
{
    private $mapping;

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function transform($value)
    {
        if (!array_key_exists($value, $this->mapping)) {
            throw new TransformationFailedException(sprintf('No mapping for value "%s"', $value));
        }

        return $this->mapping[$value];
    }

    public function reverseTransform($value)
    {
        $result = array_search($value, $this->mapping, true);

        if ($result === false) {
            throw new TransformationFailedException(sprintf('No reverse mapping for value "%s"', $value));
        }

        return $result;
    }
}
