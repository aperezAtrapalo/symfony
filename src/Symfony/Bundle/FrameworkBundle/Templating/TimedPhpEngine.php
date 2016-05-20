<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Templating;

use Makhan\Component\Templating\TemplateNameParserInterface;
use Makhan\Component\Stopwatch\Stopwatch;
use Makhan\Component\Templating\Loader\LoaderInterface;
use Makhan\Component\DependencyInjection\ContainerInterface;

/**
 * Times the time spent to render a template.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class TimedPhpEngine extends PhpEngine
{
    protected $stopwatch;

    /**
     * Constructor.
     *
     * @param TemplateNameParserInterface $parser    A TemplateNameParserInterface instance
     * @param ContainerInterface          $container A ContainerInterface instance
     * @param LoaderInterface             $loader    A LoaderInterface instance
     * @param Stopwatch                   $stopwatch A Stopwatch instance
     * @param GlobalVariables             $globals   A GlobalVariables instance
     */
    public function __construct(TemplateNameParserInterface $parser, ContainerInterface $container, LoaderInterface $loader, Stopwatch $stopwatch, GlobalVariables $globals = null)
    {
        parent::__construct($parser, $container, $loader, $globals);

        $this->stopwatch = $stopwatch;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = array())
    {
        $e = $this->stopwatch->start(sprintf('template.php (%s)', $name), 'template');

        $ret = parent::render($name, $parameters);

        $e->stop();

        return $ret;
    }
}
