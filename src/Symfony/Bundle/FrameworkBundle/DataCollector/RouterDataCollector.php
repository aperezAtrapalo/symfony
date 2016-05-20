<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\DataCollector;

use Makhan\Component\HttpKernel\DataCollector\RouterDataCollector as BaseRouterDataCollector;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Bundle\FrameworkBundle\Controller\RedirectController;

/**
 * RouterDataCollector.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class RouterDataCollector extends BaseRouterDataCollector
{
    public function guessRoute(Request $request, $controller)
    {
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof RedirectController) {
            return $request->attributes->get('_route');
        }

        return parent::guessRoute($request, $controller);
    }
}
