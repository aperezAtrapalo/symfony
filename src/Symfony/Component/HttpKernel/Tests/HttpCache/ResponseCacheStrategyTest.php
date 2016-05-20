<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * This code is partially based on the Rack-Cache library by Ryan Tomayko,
 * which is released under the MIT license.
 * (based on commit 02d2b48d75bcb63cf1c0c7149c077ad256542801)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\HttpCache;

use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpKernel\HttpCache\ResponseCacheStrategy;

class ResponseCacheStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testMinimumSharedMaxAgeWins()
    {
        $cacheStrategy = new ResponseCacheStrategy();

        $response1 = new Response();
        $response1->setSharedMaxAge(60);
        $cacheStrategy->add($response1);

        $response2 = new Response();
        $response2->setSharedMaxAge(3600);
        $cacheStrategy->add($response2);

        $response = new Response();
        $response->setSharedMaxAge(86400);
        $cacheStrategy->update($response);

        $this->assertSame('60', $response->headers->getCacheControlDirective('s-maxage'));
    }

    public function testSharedMaxAgeNotSetIfNotSetInAnyEmbeddedRequest()
    {
        $cacheStrategy = new ResponseCacheStrategy();

        $response1 = new Response();
        $response1->setSharedMaxAge(60);
        $cacheStrategy->add($response1);

        $response2 = new Response();
        $cacheStrategy->add($response2);

        $response = new Response();
        $response->setSharedMaxAge(86400);
        $cacheStrategy->update($response);

        $this->assertFalse($response->headers->hasCacheControlDirective('s-maxage'));
    }

    public function testSharedMaxAgeNotSetIfNotSetInMasterRequest()
    {
        $cacheStrategy = new ResponseCacheStrategy();

        $response1 = new Response();
        $response1->setSharedMaxAge(60);
        $cacheStrategy->add($response1);

        $response2 = new Response();
        $response2->setSharedMaxAge(3600);
        $cacheStrategy->add($response2);

        $response = new Response();
        $cacheStrategy->update($response);

        $this->assertFalse($response->headers->hasCacheControlDirective('s-maxage'));
    }
}
