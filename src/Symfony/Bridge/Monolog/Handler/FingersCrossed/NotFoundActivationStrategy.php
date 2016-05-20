<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Monolog\Handler\FingersCrossed;

use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Makhan\Component\HttpKernel\Exception\HttpException;
use Makhan\Component\HttpFoundation\RequestStack;

/**
 * Activation strategy that ignores 404s for certain URLs.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Fabien Potencier <fabien@makhan.com>
 */
class NotFoundActivationStrategy extends ErrorLevelActivationStrategy
{
    private $blacklist;
    private $requestStack;

    public function __construct(RequestStack $requestStack, array $excludedUrls, $actionLevel)
    {
        parent::__construct($actionLevel);

        $this->requestStack = $requestStack;
        $this->blacklist = '{('.implode('|', $excludedUrls).')}i';
    }

    public function isHandlerActivated(array $record)
    {
        $isActivated = parent::isHandlerActivated($record);

        if (
            $isActivated
            && isset($record['context']['exception'])
            && $record['context']['exception'] instanceof HttpException
            && $record['context']['exception']->getStatusCode() == 404
            && ($request = $this->requestStack->getMasterRequest())
        ) {
            return !preg_match($this->blacklist, $request->getPathInfo());
        }

        return $isActivated;
    }
}
