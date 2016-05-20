<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Twig\Form;

use Makhan\Component\Form\FormRendererEngineInterface;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface TwigRendererEngineInterface extends FormRendererEngineInterface
{
    /**
     * Sets Twig's environment.
     *
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment);
}
