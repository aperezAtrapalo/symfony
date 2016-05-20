<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\Fixtures;

use Makhan\Component\HttpKernel\Client;

class TestClient extends Client
{
    protected function getScript($request)
    {
        $script = parent::getScript($request);

        $autoload = file_exists(__DIR__.'/../../vendor/autoload.php')
            ? __DIR__.'/../../vendor/autoload.php'
            : __DIR__.'/../../../../../../vendor/autoload.php'
        ;

        $script = preg_replace('/(\->register\(\);)/', "$0\nrequire_once '$autoload';\n", $script);

        return $script;
    }
}
