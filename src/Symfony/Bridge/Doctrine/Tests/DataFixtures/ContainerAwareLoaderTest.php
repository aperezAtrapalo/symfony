<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Tests\DataFixtures;

use Makhan\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Makhan\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

class ContainerAwareLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldSetContainerOnContainerAwareFixture()
    {
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');
        $loader = new ContainerAwareLoader($container);
        $fixture = new ContainerAwareFixture();

        $loader->addFixture($fixture);

        $this->assertSame($container, $fixture->container);
    }
}
