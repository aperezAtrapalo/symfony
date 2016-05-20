<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle\CacheWarmer;

use Makhan\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Generates the Twig cache for all templates.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class TemplateCacheWarmer implements CacheWarmerInterface
{
    private $twig;
    private $iterator;

    public function __construct(\Twig_Environment $twig, \Traversable $iterator)
    {
        $this->twig = $twig;
        $this->iterator = $iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        foreach ($this->iterator as $template) {
            try {
                $this->twig->loadTemplate($template);
            } catch (\Twig_Error $e) {
                // problem during compilation, give up
                // might be a syntax error or a non-Twig template
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return true;
    }
}
