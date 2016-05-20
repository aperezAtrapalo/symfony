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

/**
 * Role is a simple implementation of a RoleInterface where the role is a
 * string.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class Role implements RoleInterface
{
    private $role;

    /**
     * Constructor.
     *
     * @param string $role The role name
     */
    public function __construct($role)
    {
        $this->role = (string) $role;
    }

    /**
     * {@inheritdoc}
     */
    public function getRole()
    {
        return $this->role;
    }
}
