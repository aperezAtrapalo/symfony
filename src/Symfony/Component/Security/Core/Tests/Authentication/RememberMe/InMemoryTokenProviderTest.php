<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Core\Tests\Authentication\RememberMe;

use Makhan\Component\Security\Core\Authentication\RememberMe\PersistentToken;
use Makhan\Component\Security\Core\Authentication\RememberMe\InMemoryTokenProvider;

class InMemoryTokenProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateNewToken()
    {
        $provider = new InMemoryTokenProvider();

        $token = new PersistentToken('foo', 'foo', 'foo', 'foo', new \DateTime());
        $provider->createNewToken($token);

        $this->assertSame($provider->loadTokenBySeries('foo'), $token);
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\TokenNotFoundException
     */
    public function testLoadTokenBySeriesThrowsNotFoundException()
    {
        $provider = new InMemoryTokenProvider();
        $provider->loadTokenBySeries('foo');
    }

    public function testUpdateToken()
    {
        $provider = new InMemoryTokenProvider();

        $token = new PersistentToken('foo', 'foo', 'foo', 'foo', new \DateTime());
        $provider->createNewToken($token);
        $provider->updateToken('foo', 'newFoo', $lastUsed = new \DateTime());
        $token = $provider->loadTokenBySeries('foo');

        $this->assertEquals('newFoo', $token->getTokenValue());
        $this->assertSame($token->getLastUsed(), $lastUsed);
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\TokenNotFoundException
     */
    public function testDeleteToken()
    {
        $provider = new InMemoryTokenProvider();

        $token = new PersistentToken('foo', 'foo', 'foo', 'foo', new \DateTime());
        $provider->createNewToken($token);
        $provider->deleteTokenBySeries('foo');
        $provider->loadTokenBySeries('foo');
    }
}
