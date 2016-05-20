<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Event;

use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\Security\Core\User\UserInterface;
use Makhan\Component\EventDispatcher\Event;

/**
 * SwitchUserEvent.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class SwitchUserEvent extends Event
{
    private $request;
    private $targetUser;

    public function __construct(Request $request, UserInterface $targetUser)
    {
        $this->request = $request;
        $this->targetUser = $targetUser;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return UserInterface
     */
    public function getTargetUser()
    {
        return $this->targetUser;
    }
}
