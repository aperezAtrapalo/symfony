<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle;

use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddConstraintValidatorsPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddValidatorInitializersPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddConsoleCommandPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\CachePoolPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\ControllerArgumentValueResolverPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\FormPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\PropertyInfoPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplatingPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\RoutingResolverPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\ProfilerPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\TranslatorPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\LoggingTranslatorPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddCacheWarmerPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddCacheClearerPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddExpressionLanguageProvidersPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\ContainerBuilderDebugDumpPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\CompilerDebugDumpPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\TranslationExtractorPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\TranslationDumperPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\SerializerPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\UnusedTagsPass;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\ConfigCachePass;
use Makhan\Component\Debug\ErrorHandler;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Compiler\PassConfig;
use Makhan\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Makhan\Component\HttpKernel\DependencyInjection\FragmentRendererPass;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class FrameworkBundle extends Bundle
{
    public function boot()
    {
        ErrorHandler::register(null, false)->throwAt($this->container->getParameter('debug.error_handler.throw_at'), true);

        if ($trustedProxies = $this->container->getParameter('kernel.trusted_proxies')) {
            Request::setTrustedProxies($trustedProxies);
        }

        if ($this->container->getParameter('kernel.http_method_override')) {
            Request::enableHttpMethodParameterOverride();
        }

        if ($trustedHosts = $this->container->getParameter('kernel.trusted_hosts')) {
            Request::setTrustedHosts($trustedHosts);
        }
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RoutingResolverPass());
        $container->addCompilerPass(new ProfilerPass());
        // must be registered before removing private services as some might be listeners/subscribers
        // but as late as possible to get resolved parameters
        $container->addCompilerPass(new RegisterListenersPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new TemplatingPass());
        $container->addCompilerPass(new AddConstraintValidatorsPass());
        $container->addCompilerPass(new AddValidatorInitializersPass());
        $container->addCompilerPass(new AddConsoleCommandPass());
        $container->addCompilerPass(new FormPass());
        $container->addCompilerPass(new TranslatorPass());
        $container->addCompilerPass(new LoggingTranslatorPass());
        $container->addCompilerPass(new AddCacheWarmerPass());
        $container->addCompilerPass(new AddCacheClearerPass());
        $container->addCompilerPass(new AddExpressionLanguageProvidersPass());
        $container->addCompilerPass(new TranslationExtractorPass());
        $container->addCompilerPass(new TranslationDumperPass());
        $container->addCompilerPass(new FragmentRendererPass(), PassConfig::TYPE_AFTER_REMOVING);
        $container->addCompilerPass(new SerializerPass());
        $container->addCompilerPass(new PropertyInfoPass());
        $container->addCompilerPass(new ControllerArgumentValueResolverPass());
        $container->addCompilerPass(new CachePoolPass());

        if ($container->getParameter('kernel.debug')) {
            $container->addCompilerPass(new UnusedTagsPass(), PassConfig::TYPE_AFTER_REMOVING);
            $container->addCompilerPass(new ContainerBuilderDebugDumpPass(), PassConfig::TYPE_AFTER_REMOVING);
            $container->addCompilerPass(new CompilerDebugDumpPass(), PassConfig::TYPE_AFTER_REMOVING);
            $container->addCompilerPass(new ConfigCachePass());
        }
    }
}
