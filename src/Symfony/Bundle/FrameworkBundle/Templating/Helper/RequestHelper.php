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

use Makhan\Component\Templating\Helper\Helper;
use Makhan\Component\HttpFoundation\RequestStack;

/**
 * RequestHelper provides access to the current request parameters.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class RequestHelper extends Helper
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Returns a parameter from the current request object.
     *
     * @param string $key     The name of the parameter
     * @param string $default A default value
     *
     * @return mixed
     *
     * @see Request::get()
     */
    public function getParameter($key, $default = null)
    {
        return $this->getRequest()->get($key, $default);
    }

    /**
     * Returns the locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getRequest()->getLocale();
    }

    private function getRequest()
    {
        if (!$this->requestStack->getCurrentRequest()) {
            throw new \LogicException('A Request must be available.');
        }

        return $this->requestStack->getCurrentRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'request';
    }
}
