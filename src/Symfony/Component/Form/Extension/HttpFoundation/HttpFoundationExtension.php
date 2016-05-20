<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\HttpFoundation;

use Makhan\Component\Form\AbstractExtension;

/**
 * Integrates the HttpFoundation component with the Form library.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class HttpFoundationExtension extends AbstractExtension
{
    protected function loadTypeExtensions()
    {
        return array(
            new Type\FormTypeHttpFoundationExtension(),
        );
    }
}
