<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Twig\Tests\NodeVisitor;

use Makhan\Bridge\Twig\NodeVisitor\Scope;

class ScopeTest extends \PHPUnit_Framework_TestCase
{
    public function testScopeInitiation()
    {
        $scope = new Scope();
        $scope->enter();
        $this->assertNull($scope->get('test'));
    }
}
