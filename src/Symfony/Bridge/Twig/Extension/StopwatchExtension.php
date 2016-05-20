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

use Makhan\Component\Stopwatch\Stopwatch;
use Makhan\Bridge\Twig\TokenParser\StopwatchTokenParser;

/**
 * Twig extension for the stopwatch helper.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class StopwatchExtension extends \Twig_Extension
{
    private $stopwatch;

    /**
     * @var bool
     */
    private $enabled;

    public function __construct(Stopwatch $stopwatch = null, $enabled = true)
    {
        $this->stopwatch = $stopwatch;
        $this->enabled = $enabled;
    }

    public function getStopwatch()
    {
        return $this->stopwatch;
    }

    public function getTokenParsers()
    {
        return array(
            /*
             * {% stopwatch foo %}
             * Some stuff which will be recorded on the timeline
             * {% endstopwatch %}
             */
            new StopwatchTokenParser($this->stopwatch !== null && $this->enabled),
        );
    }

    public function getName()
    {
        return 'stopwatch';
    }
}
