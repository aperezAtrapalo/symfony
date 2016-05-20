<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Guard\Tests\Authenticator;

use Makhan\Component\HttpFoundation\RedirectResponse;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\Security\Core\User\UserInterface;
use Makhan\Component\Security\Core\User\UserProviderInterface;
use Makhan\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class AbstractFormLoginAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group legacy
     */
    public function testLegacyWithLoginUrl()
    {
        $request = new Request();
        $request->setSession($this->getMock('Makhan\Component\HttpFoundation\Session\Session'));

        $authenticator = new LegacyFormLoginAuthenticator();
        /** @var RedirectResponse $actualResponse */
        $actualResponse = $authenticator->onAuthenticationSuccess(
            $request,
            $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface'),
            'provider_key'
        );

        $this->assertEquals('/default_url', $actualResponse->getTargetUrl());
    }
}

class LegacyFormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    protected function getDefaultSuccessRedirectUrl()
    {
        return '/default_url';
    }

    protected function getLoginUrl()
    {
    }

    public function getCredentials(Request $request)
    {
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
    }
}
