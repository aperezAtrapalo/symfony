<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Validator;

use Makhan\Component\Form\Extension\Validator\Constraints\Form;
use Makhan\Component\Form\AbstractExtension;
use Makhan\Component\Validator\Constraints\Valid;
use Makhan\Component\Validator\Mapping\ClassMetadata;
use Makhan\Component\Validator\Validator\ValidatorInterface;

/**
 * Extension supporting the Makhan Validator component in forms.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ValidatorExtension extends AbstractExtension
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $metadata = $validator->getMetadataFor('Makhan\Component\Form\Form');

        // Register the form constraints in the validator programmatically.
        // This functionality is required when using the Form component without
        // the DIC, where the XML file is loaded automatically. Thus the following
        // code must be kept synchronized with validation.xml

        /* @var $metadata ClassMetadata */
        $metadata->addConstraint(new Form());
        $metadata->addPropertyConstraint('children', new Valid());

        $this->validator = $validator;
    }

    public function loadTypeGuesser()
    {
        return new ValidatorTypeGuesser($this->validator);
    }

    protected function loadTypeExtensions()
    {
        return array(
            new Type\FormTypeValidatorExtension($this->validator),
            new Type\RepeatedTypeValidatorExtension(),
            new Type\SubmitTypeValidatorExtension(),
        );
    }
}
