<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Test;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\EntityManager;

/**
 * Provides utility functions needed in tests.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DoctrineTestHelper
{
    /**
     * Returns an entity manager for testing.
     *
     * @return EntityManager
     */
    public static function createTestEntityManager()
    {
        if (!extension_loaded('pdo_sqlite')) {
            \PHPUnit_Framework_TestCase::markTestSkipped('Extension pdo_sqlite is required.');
        }

        $config = new \Doctrine\ORM\Configuration();
        $config->setEntityNamespaces(array('MakhanTestsDoctrine' => 'Makhan\Bridge\Doctrine\Tests\Fixtures'));
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyDir(\sys_get_temp_dir());
        $config->setProxyNamespace('MakhanTests\Doctrine');
        $config->setMetadataDriverImpl(new AnnotationDriver(new AnnotationReader()));
        $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
        $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());

        $params = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        return EntityManager::create($params, $config);
    }

    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}
