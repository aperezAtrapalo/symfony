<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Role;

use Makhan\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * SwitchUserRole is used when the current user temporarily impersonates
 * another one.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class SwitchUserRole extends Role
{
    private $source;

    /**
     * Constructor.
     *
     * @param string         $role   The role as a string
     * @param TokenInterface $source The original token
     */
    public function __construct($role, TokenInterface $source)
    {
        parent::__construct($role);

        $this->source = $source;
    }

    /**
     * Returns the original Token.
     *
     * @return TokenInterface The original TokenInterface instance
     */
    public function getSource()
    {
        return $this->source;
    }
}
