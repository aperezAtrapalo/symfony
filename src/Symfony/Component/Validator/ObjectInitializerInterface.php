<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator;

/**
 * Prepares an object for validation.
 *
 * Concrete implementations of this interface are used by {@link ValidationVisitorInterface}
 * to initialize objects just before validating them.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface ObjectInitializerInterface
{
    /**
     * Initializes an object just before validation.
     *
     * @param object $object The object to validate
     */
    public function initialize($object);
}
