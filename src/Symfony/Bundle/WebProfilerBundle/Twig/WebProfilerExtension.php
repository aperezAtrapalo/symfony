<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\WebProfilerBundle\Twig;

use Makhan\Component\HttpKernel\DataCollector\Util\ValueExporter;

/**
 * Twig extension for the profiler.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class WebProfilerExtension extends \Twig_Extension
{
    /**
     * @var ValueExporter
     */
    private $valueExporter;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('profiler_dump', array($this, 'dumpValue')),
        );
    }

    public function dumpValue($value)
    {
        if (null === $this->valueExporter) {
            $this->valueExporter = new ValueExporter();
        }

        return $this->valueExporter->exportValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'profiler';
    }
}
