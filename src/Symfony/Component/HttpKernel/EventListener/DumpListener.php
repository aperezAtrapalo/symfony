<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\EventListener;

use Makhan\Component\EventDispatcher\EventSubscriberInterface;
use Makhan\Component\HttpKernel\KernelEvents;
use Makhan\Component\VarDumper\Cloner\ClonerInterface;
use Makhan\Component\VarDumper\Dumper\DataDumperInterface;
use Makhan\Component\VarDumper\VarDumper;

/**
 * Configures dump() handler.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DumpListener implements EventSubscriberInterface
{
    private $cloner;
    private $dumper;

    /**
     * @param ClonerInterface     $cloner Cloner service.
     * @param DataDumperInterface $dumper Dumper service.
     */
    public function __construct(ClonerInterface $cloner, DataDumperInterface $dumper)
    {
        $this->cloner = $cloner;
        $this->dumper = $dumper;
    }

    public function configure()
    {
        $cloner = $this->cloner;
        $dumper = $this->dumper;

        VarDumper::setHandler(function ($var) use ($cloner, $dumper) {
            $dumper->dump($cloner->cloneVar($var));
        });
    }

    public static function getSubscribedEvents()
    {
        // Register early to have a working dump() as early as possible
        return array(KernelEvents::REQUEST => array('configure', 1024));
    }
}
