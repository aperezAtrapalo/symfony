<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Tests\Authentication\Token\Storage;

use Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TokenStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetToken()
    {
        $tokenStorage = new TokenStorage();
        $this->assertNull($tokenStorage->getToken());
        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
        $tokenStorage->setToken($token);
        $this->assertSame($token, $tokenStorage->getToken());
    }
}
