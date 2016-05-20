<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Kernel;

use Makhan\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Makhan\Bundle\FrameworkBundle\FrameworkBundle;
use Makhan\Component\Config\Loader\LoaderInterface;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\Filesystem\Filesystem;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpKernel\Kernel;
use Makhan\Component\Routing\RouteCollectionBuilder;

class ConcreteMicroKernel extends Kernel
{
    use MicroKernelTrait;

    private $cacheDir;

    public function halloweenAction()
    {
        return new Response('halloween');
    }

    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
        );
    }

    public function getCacheDir()
    {
        return $this->cacheDir = sys_get_temp_dir().'/sf_micro_kernel';
    }

    public function getLogDir()
    {
        return $this->cacheDir;
    }

    public function __destruct()
    {
        $fs = new Filesystem();
        $fs->remove($this->cacheDir);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->add('/', 'kernel:halloweenAction');
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', array(
            'secret' => '$ecret',
        ));

        $c->setParameter('halloween', 'Have a great day!');
        $c->register('halloween', 'stdClass');
    }
}
