<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Authentication\Provider;

use Makhan\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Makhan\Component\Security\Core\Exception\BadCredentialsException;
use Makhan\Component\Security\Core\Exception\UsernameNotFoundException;
use Makhan\Component\Security\Core\User\UserCheckerInterface;
use Makhan\Component\Security\Core\User\UserInterface;
use Makhan\Component\Security\Core\User\UserProviderInterface;
use Makhan\Component\Ldap\LdapInterface;
use Makhan\Component\Ldap\Exception\ConnectionException;

/**
 * LdapBindAuthenticationProvider authenticates a user against an LDAP server.
 *
 * The only way to check user credentials is to try to connect the user with its
 * credentials to the ldap.
 *
 * @author Charles Sarrazin <charles@sarraz.in>
 */
class LdapBindAuthenticationProvider extends UserAuthenticationProvider
{
    private $userProvider;
    private $ldap;
    private $dnString;

    /**
     * Constructor.
     *
     * @param UserProviderInterface $userProvider               A UserProvider
     * @param UserCheckerInterface  $userChecker                A UserChecker
     * @param string                $providerKey                The provider key
     * @param LdapInterface         $ldap                       A Ldap client
     * @param string                $dnString                   A string used to create the bind DN
     * @param bool                  $hideUserNotFoundExceptions Whether to hide user not found exception or not
     */
    public function __construct(UserProviderInterface $userProvider, UserCheckerInterface $userChecker, $providerKey, LdapInterface $ldap, $dnString = '{username}', $hideUserNotFoundExceptions = true)
    {
        parent::__construct($userChecker, $providerKey, $hideUserNotFoundExceptions);

        $this->userProvider = $userProvider;
        $this->ldap = $ldap;
        $this->dnString = $dnString;
    }

    /**
     * {@inheritdoc}
     */
    protected function retrieveUser($username, UsernamePasswordToken $token)
    {
        if (AuthenticationProviderInterface::USERNAME_NONE_PROVIDED === $username) {
            throw new UsernameNotFoundException('Username can not be null');
        }

        return $this->userProvider->loadUserByUsername($username);
    }

    /**
     * {@inheritdoc}
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        $username = $token->getUsername();
        $password = $token->getCredentials();

        if ('' === $password) {
            throw new BadCredentialsException('The presented password must not be empty.');
        }

        try {
            $username = $this->ldap->escape($username, '', LdapInterface::ESCAPE_DN);
            $dn = str_replace('{username}', $username, $this->dnString);

            $this->ldap->bind($dn, $password);
        } catch (ConnectionException $e) {
            throw new BadCredentialsException('The presented password is invalid.');
        }
    }
}
