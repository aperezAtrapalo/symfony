<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller;

use Makhan\Component\DependencyInjection\ContainerAwareInterface;
use Makhan\Component\DependencyInjection\ContainerAwareTrait;
use Makhan\Component\HttpFoundation\Response;

class ProfilerController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function indexAction()
    {
        return new Response('Hello');
    }
}
