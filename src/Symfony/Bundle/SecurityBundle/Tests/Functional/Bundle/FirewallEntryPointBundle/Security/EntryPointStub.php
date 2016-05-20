<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Tests\Functional\Bundle\FirewallEntryPointBundle\Security;

use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\Security\Core\Exception\AuthenticationException;
use Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class EntryPointStub implements AuthenticationEntryPointInterface
{
    const RESPONSE_TEXT = '2be8e651259189d841a19eecdf37e771e2431741';

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response(self::RESPONSE_TEXT);
    }
}
