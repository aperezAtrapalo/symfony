<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\Fixtures\ExtensionNotValidBundle\DependencyInjection;

class ExtensionNotValidExtension
{
    public function getAlias()
    {
        return 'extension_not_valid';
    }
}
