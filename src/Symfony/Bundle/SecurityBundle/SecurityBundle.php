<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle;

use Makhan\Component\HttpKernel\Bundle\Bundle;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Compiler\AddSecurityVotersPass;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginLdapFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\HttpBasicFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\HttpBasicLdapFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\HttpDigestFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\RememberMeFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\X509Factory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\RemoteUserFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SimplePreAuthenticationFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SimpleFormFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\InMemoryFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\Factory\GuardAuthenticationFactory;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\LdapFactory;

/**
 * Bundle.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class SecurityBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new FormLoginFactory());
        $extension->addSecurityListenerFactory(new FormLoginLdapFactory());
        $extension->addSecurityListenerFactory(new HttpBasicFactory());
        $extension->addSecurityListenerFactory(new HttpBasicLdapFactory());
        $extension->addSecurityListenerFactory(new HttpDigestFactory());
        $extension->addSecurityListenerFactory(new RememberMeFactory());
        $extension->addSecurityListenerFactory(new X509Factory());
        $extension->addSecurityListenerFactory(new RemoteUserFactory());
        $extension->addSecurityListenerFactory(new SimplePreAuthenticationFactory());
        $extension->addSecurityListenerFactory(new SimpleFormFactory());
        $extension->addSecurityListenerFactory(new GuardAuthenticationFactory());

        $extension->addUserProviderFactory(new InMemoryFactory());
        $extension->addUserProviderFactory(new LdapFactory());
        $container->addCompilerPass(new AddSecurityVotersPass());
    }
}
