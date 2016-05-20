<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Templating;

use Makhan\Component\Form\AbstractExtension;
use Makhan\Component\Form\FormRenderer;
use Makhan\Component\Security\Csrf\CsrfTokenManagerInterface;
use Makhan\Component\Templating\PhpEngine;
use Makhan\Bundle\FrameworkBundle\Templating\Helper\FormHelper;

/**
 * Integrates the Templating component with the Form library.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TemplatingExtension extends AbstractExtension
{
    public function __construct(PhpEngine $engine, CsrfTokenManagerInterface $csrfTokenManager = null, array $defaultThemes = array())
    {
        $engine->addHelpers(array(
            new FormHelper(new FormRenderer(new TemplatingRendererEngine($engine, $defaultThemes), $csrfTokenManager)),
        ));
    }
}
