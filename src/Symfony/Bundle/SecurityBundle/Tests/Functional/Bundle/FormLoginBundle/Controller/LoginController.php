<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Tests\Functional\Bundle\FormLoginBundle\Controller;

use Makhan\Component\DependencyInjection\ContainerAwareInterface;
use Makhan\Component\DependencyInjection\ContainerAwareTrait;
use Makhan\Component\Security\Core\Exception\AccessDeniedException;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\Security\Core\Security;

class LoginController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function loginAction(Request $request)
    {
        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(Security::AUTHENTICATION_ERROR);
        }

        return $this->container->get('templating')->renderResponse('FormLoginBundle:Login:login.html.twig', array(
            // last username entered by the user
            'last_username' => $request->getSession()->get(Security::LAST_USERNAME),
            'error' => $error,
        ));
    }

    public function afterLoginAction()
    {
        return $this->container->get('templating')->renderResponse('FormLoginBundle:Login:after_login.html.twig');
    }

    public function loginCheckAction()
    {
        return new Response('', 400);
    }

    public function secureAction()
    {
        throw new \Exception('Wrapper', 0, new \Exception('Another Wrapper', 0, new AccessDeniedException()));
    }
}
