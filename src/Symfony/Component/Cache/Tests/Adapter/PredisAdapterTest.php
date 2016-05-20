<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Cache\Tests\Adapter;

use Predis\Connection\StreamConnection;
use Makhan\Component\Cache\Adapter\RedisAdapter;

class PredisAdapterTest extends AbstractRedisAdapterTest
{
    public static function setupBeforeClass()
    {
        parent::setupBeforeClass();
        self::$redis = new \Predis\Client();
    }

    public function testCreateConnection()
    {
        $redis = RedisAdapter::createConnection('redis://localhost/1', array('class' => \Predis\Client::class, 'timeout' => 3));
        $this->assertInstanceOf(\Predis\Client::class, $redis);

        $connection = $redis->getConnection();
        $this->assertInstanceOf(StreamConnection::class, $connection);

        $params = array(
            'scheme' => 'tcp',
            'host' => 'localhost',
            'path' => '',
            'dbindex' => '1',
            'port' => 6379,
            'class' => 'Predis\Client',
            'timeout' => 3,
            'persistent' => 0,
            'read_timeout' => 0,
            'retry_interval' => 0,
            'database' => '1',
            'password' => null,
        );
        $this->assertSame($params, $connection->getParameters()->toArray());
    }
}
