<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\EventListener;

use Makhan\Component\HttpKernel\EventListener\SessionListener as BaseSessionListener;
use Makhan\Component\DependencyInjection\ContainerInterface;

/**
 * Sets the session in the request.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class SessionListener extends BaseSessionListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getSession()
    {
        if (!$this->container->has('session')) {
            return;
        }

        return $this->container->get('session');
    }
}
