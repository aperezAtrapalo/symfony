<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Ldap;

use Makhan\Component\Ldap\Adapter\AdapterInterface;
use Makhan\Component\Ldap\Exception\DriverNotFoundException;

/**
 * @author Charles Sarrazin <charles@sarraz.in>
 */
final class Ldap implements LdapInterface
{
    private $adapter;

    private static $adapterMap = array(
        'ext_ldap' => 'Makhan\Component\Ldap\Adapter\ExtLdap\Adapter',
    );

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function bind($dn = null, $password = null)
    {
        $this->adapter->getConnection()->bind($dn, $password);
    }

    /**
     * {@inheritdoc}
     */
    public function query($dn, $query, array $options = array())
    {
        return $this->adapter->createQuery($dn, $query, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntryManager()
    {
        return $this->adapter->getEntryManager();
    }

    /**
     * {@inheritdoc}
     */
    public function escape($subject, $ignore = '', $flags = 0)
    {
        return $this->adapter->escape($subject, $ignore, $flags);
    }

    /**
     * Creates a new Ldap instance.
     *
     * @param string $adapter The adapter name
     * @param array  $config  The adapter's configuration
     *
     * @return static
     */
    public static function create($adapter, array $config = array())
    {
        if (!isset(self::$adapterMap[$adapter])) {
            throw new DriverNotFoundException(sprintf(
                'Adapter "%s" not found. You should use one of: %s',
                $adapter,
                implode(', ', self::$adapterMap)
            ));
        }

        $class = self::$adapterMap[$adapter];

        return new self(new $class($config));
    }
}
