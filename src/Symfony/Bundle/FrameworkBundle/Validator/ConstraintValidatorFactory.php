<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Validator;

use Makhan\Component\DependencyInjection\ContainerInterface;
use Makhan\Component\Validator\Constraint;
use Makhan\Component\Validator\ConstraintValidatorFactoryInterface;
use Makhan\Component\Validator\ConstraintValidatorInterface;
use Makhan\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Uses a service container to create constraint validators.
 *
 * A constraint validator should be tagged as "validator.constraint_validator"
 * in the service container and include an "alias" attribute:
 *
 *     <service id="some_doctrine_validator">
 *         <argument type="service" id="doctrine.orm.some_entity_manager" />
 *         <tag name="validator.constraint_validator" alias="some_alias" />
 *     </service>
 *
 * A constraint may then return this alias in its validatedBy() method:
 *
 *     public function validatedBy()
 *     {
 *         return 'some_alias';
 *     }
 *
 * @author Kris Wallsmith <kris@makhan.com>
 */
class ConstraintValidatorFactory implements ConstraintValidatorFactoryInterface
{
    protected $container;
    protected $validators;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container  The service container
     * @param array              $validators An array of validators
     */
    public function __construct(ContainerInterface $container, array $validators = array())
    {
        $this->container = $container;
        $this->validators = $validators;
    }

    /**
     * Returns the validator for the supplied constraint.
     *
     * @param Constraint $constraint A constraint
     *
     * @return ConstraintValidatorInterface A validator for the supplied constraint
     *
     * @throws UnexpectedTypeException When the validator is not an instance of ConstraintValidatorInterface
     */
    public function getInstance(Constraint $constraint)
    {
        $name = $constraint->validatedBy();

        if (!isset($this->validators[$name])) {
            $this->validators[$name] = new $name();
        } elseif (is_string($this->validators[$name])) {
            $this->validators[$name] = $this->container->get($this->validators[$name]);
        }

        if (!$this->validators[$name] instanceof ConstraintValidatorInterface) {
            throw new UnexpectedTypeException($this->validators[$name], 'Makhan\Component\Validator\ConstraintValidatorInterface');
        }

        return $this->validators[$name];
    }
}
