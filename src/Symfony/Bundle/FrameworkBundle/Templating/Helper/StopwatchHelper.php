<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Templating\Helper;

use Makhan\Component\Stopwatch\Stopwatch;
use Makhan\Component\Templating\Helper\Helper;

/**
 * StopwatchHelper provides methods time your PHP templates.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class StopwatchHelper extends Helper
{
    private $stopwatch;

    public function __construct(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
    }

    public function getName()
    {
        return 'stopwatch';
    }

    public function __call($method, $arguments = array())
    {
        if (null !== $this->stopwatch) {
            if (method_exists($this->stopwatch, $method)) {
                return call_user_func_array(array($this->stopwatch, $method), $arguments);
            }

            throw new \BadMethodCallException(sprintf('Method "%s" of Stopwatch does not exist', $method));
        }
    }
}
