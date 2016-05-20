<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien.potencier@makhan-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\Fixtures\ExtensionPresentBundle\Command;

use Makhan\Component\Console\Command\Command;

class FooCommand extends Command
{
    protected function configure()
    {
        $this->setName('foo');
    }
}
