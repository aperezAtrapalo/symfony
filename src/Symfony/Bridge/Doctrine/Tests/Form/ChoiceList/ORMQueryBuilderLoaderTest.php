<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Tests\Form\ChoiceList;

use Makhan\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Makhan\Bridge\Doctrine\Test\DoctrineTestHelper;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Version;

class ORMQueryBuilderLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testIdentifierTypeIsStringArray()
    {
        $this->checkIdentifierType('Makhan\Bridge\Doctrine\Tests\Fixtures\SingleStringIdEntity', Connection::PARAM_STR_ARRAY);
    }

    public function testIdentifierTypeIsIntegerArray()
    {
        $this->checkIdentifierType('Makhan\Bridge\Doctrine\Tests\Fixtures\SingleIntIdEntity', Connection::PARAM_INT_ARRAY);
    }

    protected function checkIdentifierType($classname, $expectedType)
    {
        $em = DoctrineTestHelper::createTestEntityManager();

        $query = $this->getMockBuilder('QueryMock')
            ->setMethods(array('setParameter', 'getResult', 'getSql', '_doExecute'))
            ->getMock();

        $query->expects($this->once())
            ->method('setParameter')
            ->with('ORMQueryBuilderLoader_getEntitiesByIds_id', array(1, 2), $expectedType)
            ->willReturn($query);

        $qb = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->setConstructorArgs(array($em))
            ->setMethods(array('getQuery'))
            ->getMock();

        $qb->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $qb->select('e')
            ->from($classname, 'e');

        $loader = new ORMQueryBuilderLoader($qb);
        $loader->getEntitiesByIds('id', array(1, 2));
    }

    public function testFilterNonIntegerValues()
    {
        $em = DoctrineTestHelper::createTestEntityManager();

        $query = $this->getMockBuilder('QueryMock')
            ->setMethods(array('setParameter', 'getResult', 'getSql', '_doExecute'))
            ->getMock();

        $query->expects($this->once())
            ->method('setParameter')
            ->with('ORMQueryBuilderLoader_getEntitiesByIds_id', array(1, 2, 3), Connection::PARAM_INT_ARRAY)
            ->willReturn($query);

        $qb = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->setConstructorArgs(array($em))
            ->setMethods(array('getQuery'))
            ->getMock();

        $qb->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $qb->select('e')
            ->from('Makhan\Bridge\Doctrine\Tests\Fixtures\SingleIntIdEntity', 'e');

        $loader = new ORMQueryBuilderLoader($qb);
        $loader->getEntitiesByIds('id', array(1, '', 2, 3, 'foo'));
    }

    public function testEmbeddedIdentifierName()
    {
        if (Version::compare('2.5.0') > 0) {
            $this->markTestSkipped('Applicable only for Doctrine >= 2.5.0');

            return;
        }

        $em = DoctrineTestHelper::createTestEntityManager();

        $query = $this->getMockBuilder('QueryMock')
            ->setMethods(array('setParameter', 'getResult', 'getSql', '_doExecute'))
            ->getMock();

        $query->expects($this->once())
            ->method('setParameter')
            ->with('ORMQueryBuilderLoader_getEntitiesByIds_id_value', array(1, 2, 3), Connection::PARAM_INT_ARRAY)
            ->willReturn($query);

        $qb = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->setConstructorArgs(array($em))
            ->setMethods(array('getQuery'))
            ->getMock();
        $qb->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $qb->select('e')
            ->from('Makhan\Bridge\Doctrine\Tests\Fixtures\EmbeddedIdentifierEntity', 'e');

        $loader = new ORMQueryBuilderLoader($qb);
        $loader->getEntitiesByIds('id.value', array(1, '', 2, 3, 'foo'));
    }
}
