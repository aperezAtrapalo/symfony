<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Tests\Functional\Bundle\FormLoginBundle\Security;

use Makhan\Component\HttpFoundation\RedirectResponse;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\Routing\Generator\UrlGeneratorInterface;
use Makhan\Component\Routing\RouterInterface;
use Makhan\Component\Security\Core\Exception\AuthenticationException;
use Makhan\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class LocalizedFormFailureHandler implements AuthenticationFailureHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse($this->router->generate('localized_login_path', array(), UrlGeneratorInterface::ABSOLUTE_URL));
    }
}
