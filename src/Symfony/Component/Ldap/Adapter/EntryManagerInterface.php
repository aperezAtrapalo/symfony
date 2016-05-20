<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Ldap\Adapter;

use Makhan\Component\Ldap\Entry;

/**
 * Entry manager interface.
 *
 * @author Charles Sarrazin <charles@sarraz.in>
 */
interface EntryManagerInterface
{
    /**
     * Adds a new entry in the Ldap server.
     *
     * @param Entry $entry
     */
    public function add(Entry $entry);

    /**
     * Updates an entry from the Ldap server.
     *
     * @param Entry $entry
     */
    public function update(Entry $entry);

    /**
     * Removes an entry from the Ldap server.
     *
     * @param Entry $entry
     */
    public function remove(Entry $entry);
}
