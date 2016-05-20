<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Monolog\Handler;

use Monolog\Handler\SwiftMailerHandler as BaseSwiftMailerHandler;
use Makhan\Component\Console\Event\ConsoleTerminateEvent;
use Makhan\Component\HttpKernel\Event\PostResponseEvent;

/**
 * Extended SwiftMailerHandler that flushes mail queue if necessary.
 *
 * @author Philipp Kräutli <pkraeutli@astina.ch>
 */
class SwiftMailerHandler extends BaseSwiftMailerHandler
{
    protected $transport;

    protected $instantFlush = false;

    /**
     * @param \Swift_Transport $transport
     */
    public function setTransport(\Swift_Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * After the kernel has been terminated we will always flush messages.
     *
     * @param PostResponseEvent $event
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        $this->instantFlush = true;
    }

    /**
     * After the CLI application has been terminated we will always flush messages.
     *
     * @param ConsoleTerminateEvent $event
     */
    public function onCliTerminate(ConsoleTerminateEvent $event)
    {
        $this->instantFlush = true;
    }

    /**
     * {@inheritdoc}
     */
    protected function send($content, array $records)
    {
        parent::send($content, $records);

        if ($this->instantFlush) {
            $this->flushMemorySpool();
        }
    }

    /**
     * Flushes the mail queue if a memory spool is used.
     */
    private function flushMemorySpool()
    {
        $mailerTransport = $this->mailer->getTransport();
        if (!$mailerTransport instanceof \Swift_Transport_SpoolTransport) {
            return;
        }

        $spool = $mailerTransport->getSpool();
        if (!$spool instanceof \Swift_MemorySpool) {
            return;
        }

        if (null === $this->transport) {
            throw new \Exception('No transport available to flush mail queue');
        }

        $spool->flushQueue($this->transport);
    }
}
