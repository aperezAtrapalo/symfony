<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Twig\Extension;

use Makhan\Bridge\Twig\TokenParser\DumpTokenParser;
use Makhan\Component\VarDumper\Cloner\ClonerInterface;
use Makhan\Component\VarDumper\Dumper\HtmlDumper;

/**
 * Provides integration of the dump() function with Twig.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DumpExtension extends \Twig_Extension
{
    private $cloner;

    public function __construct(ClonerInterface $cloner)
    {
        $this->cloner = $cloner;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dump', array($this, 'dump'), array('is_safe' => array('html'), 'needs_context' => true, 'needs_environment' => true)),
        );
    }

    public function getTokenParsers()
    {
        return array(new DumpTokenParser());
    }

    public function getName()
    {
        return 'dump';
    }

    public function dump(\Twig_Environment $env, $context)
    {
        if (!$env->isDebug()) {
            return;
        }

        if (2 === func_num_args()) {
            $vars = array();
            foreach ($context as $key => $value) {
                if (!$value instanceof \Twig_Template) {
                    $vars[$key] = $value;
                }
            }

            $vars = array($vars);
        } else {
            $vars = func_get_args();
            unset($vars[0], $vars[1]);
        }

        $dump = fopen('php://memory', 'r+b');
        $dumper = new HtmlDumper($dump);

        foreach ($vars as $value) {
            $dumper->dump($this->cloner->cloneVar($value));
        }
        rewind($dump);

        return stream_get_contents($dump);
    }
}
