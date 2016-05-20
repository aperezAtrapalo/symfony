<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Tests\Authorization\Voter;

use Makhan\Component\Security\Core\Authentication\AuthenticationTrustResolver;
use Makhan\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Makhan\Component\Security\Core\Authorization\Voter\VoterInterface;

class AuthenticatedVoterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getVoteTests
     */
    public function testVote($authenticated, $attributes, $expected)
    {
        $voter = new AuthenticatedVoter($this->getResolver());

        $this->assertSame($expected, $voter->vote($this->getToken($authenticated), null, $attributes));
    }

    public function getVoteTests()
    {
        return array(
            array('fully', array(), VoterInterface::ACCESS_ABSTAIN),
            array('fully', array('FOO'), VoterInterface::ACCESS_ABSTAIN),
            array('remembered', array(), VoterInterface::ACCESS_ABSTAIN),
            array('remembered', array('FOO'), VoterInterface::ACCESS_ABSTAIN),
            array('anonymously', array(), VoterInterface::ACCESS_ABSTAIN),
            array('anonymously', array('FOO'), VoterInterface::ACCESS_ABSTAIN),

            array('fully', array('IS_AUTHENTICATED_ANONYMOUSLY'), VoterInterface::ACCESS_GRANTED),
            array('remembered', array('IS_AUTHENTICATED_ANONYMOUSLY'), VoterInterface::ACCESS_GRANTED),
            array('anonymously', array('IS_AUTHENTICATED_ANONYMOUSLY'), VoterInterface::ACCESS_GRANTED),

            array('fully', array('IS_AUTHENTICATED_REMEMBERED'), VoterInterface::ACCESS_GRANTED),
            array('remembered', array('IS_AUTHENTICATED_REMEMBERED'), VoterInterface::ACCESS_GRANTED),
            array('anonymously', array('IS_AUTHENTICATED_REMEMBERED'), VoterInterface::ACCESS_DENIED),

            array('fully', array('IS_AUTHENTICATED_FULLY'), VoterInterface::ACCESS_GRANTED),
            array('remembered', array('IS_AUTHENTICATED_FULLY'), VoterInterface::ACCESS_DENIED),
            array('anonymously', array('IS_AUTHENTICATED_FULLY'), VoterInterface::ACCESS_DENIED),
        );
    }

    protected function getResolver()
    {
        return new AuthenticationTrustResolver(
            'Makhan\\Component\\Security\\Core\\Authentication\\Token\\AnonymousToken',
            'Makhan\\Component\\Security\\Core\\Authentication\\Token\\RememberMeToken'
        );
    }

    protected function getToken($authenticated)
    {
        if ('fully' === $authenticated) {
            return $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
        } elseif ('remembered' === $authenticated) {
            return $this->getMock('Makhan\Component\Security\Core\Authentication\Token\RememberMeToken', array('setPersistent'), array(), '', false);
        } else {
            return $this->getMock('Makhan\Component\Security\Core\Authentication\Token\AnonymousToken', null, array('', ''));
        }
    }
}
