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

use Monolog\Handler\FirePHPHandler as BaseFirePHPHandler;
use Makhan\Component\HttpKernel\Event\FilterResponseEvent;
use Makhan\Component\HttpFoundation\Response;

/**
 * FirePHPHandler.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class FirePHPHandler extends BaseFirePHPHandler
{
    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var Response
     */
    private $response;

    /**
     * Adds the headers to the response once it's created.
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!preg_match('{\bFirePHP/\d+\.\d+\b}', $event->getRequest()->headers->get('User-Agent'))
            && !$event->getRequest()->headers->has('X-FirePHP-Version')) {
            $this->sendHeaders = false;
            $this->headers = array();

            return;
        }

        $this->response = $event->getResponse();
        foreach ($this->headers as $header => $content) {
            $this->response->headers->set($header, $content);
        }
        $this->headers = array();
    }

    /**
     * {@inheritdoc}
     */
    protected function sendHeader($header, $content)
    {
        if (!$this->sendHeaders) {
            return;
        }

        if ($this->response) {
            $this->response->headers->set($header, $content);
        } else {
            $this->headers[$header] = $content;
        }
    }

    /**
     * Override default behavior since we check the user agent in onKernelResponse.
     */
    protected function headersAccepted()
    {
        return true;
    }
}
