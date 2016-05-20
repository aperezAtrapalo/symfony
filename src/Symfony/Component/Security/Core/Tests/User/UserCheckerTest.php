<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Tests\User;

use Makhan\Component\Security\Core\User\UserChecker;

class UserCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckPostAuthNotAdvancedUserInterface()
    {
        $checker = new UserChecker();

        $this->assertNull($checker->checkPostAuth($this->getMock('Makhan\Component\Security\Core\User\UserInterface')));
    }

    public function testCheckPostAuthPass()
    {
        $checker = new UserChecker();

        $account = $this->getMock('Makhan\Component\Security\Core\User\AdvancedUserInterface');
        $account->expects($this->once())->method('isCredentialsNonExpired')->will($this->returnValue(true));

        $this->assertNull($checker->checkPostAuth($account));
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\CredentialsExpiredException
     */
    public function testCheckPostAuthCredentialsExpired()
    {
        $checker = new UserChecker();

        $account = $this->getMock('Makhan\Component\Security\Core\User\AdvancedUserInterface');
        $account->expects($this->once())->method('isCredentialsNonExpired')->will($this->returnValue(false));

        $checker->checkPostAuth($account);
    }

    public function testCheckPreAuthNotAdvancedUserInterface()
    {
        $checker = new UserChecker();

        $this->assertNull($checker->checkPreAuth($this->getMock('Makhan\Component\Security\Core\User\UserInterface')));
    }

    public function testCheckPreAuthPass()
    {
        $checker = new UserChecker();

        $account = $this->getMock('Makhan\Component\Security\Core\User\AdvancedUserInterface');
        $account->expects($this->once())->method('isAccountNonLocked')->will($this->returnValue(true));
        $account->expects($this->once())->method('isEnabled')->will($this->returnValue(true));
        $account->expects($this->once())->method('isAccountNonExpired')->will($this->returnValue(true));

        $this->assertNull($checker->checkPreAuth($account));
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\LockedException
     */
    public function testCheckPreAuthAccountLocked()
    {
        $checker = new UserChecker();

        $account = $this->getMock('Makhan\Component\Security\Core\User\AdvancedUserInterface');
        $account->expects($this->once())->method('isAccountNonLocked')->will($this->returnValue(false));

        $checker->checkPreAuth($account);
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\DisabledException
     */
    public function testCheckPreAuthDisabled()
    {
        $checker = new UserChecker();

        $account = $this->getMock('Makhan\Component\Security\Core\User\AdvancedUserInterface');
        $account->expects($this->once())->method('isAccountNonLocked')->will($this->returnValue(true));
        $account->expects($this->once())->method('isEnabled')->will($this->returnValue(false));

        $checker->checkPreAuth($account);
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\AccountExpiredException
     */
    public function testCheckPreAuthAccountExpired()
    {
        $checker = new UserChecker();

        $account = $this->getMock('Makhan\Component\Security\Core\User\AdvancedUserInterface');
        $account->expects($this->once())->method('isAccountNonLocked')->will($this->returnValue(true));
        $account->expects($this->once())->method('isEnabled')->will($this->returnValue(true));
        $account->expects($this->once())->method('isAccountNonExpired')->will($this->returnValue(false));

        $checker->checkPreAuth($account);
    }
}
