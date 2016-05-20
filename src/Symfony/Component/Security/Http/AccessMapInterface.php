<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http;

use Makhan\Component\HttpFoundation\Request;

/**
 * AccessMap allows configuration of different access control rules for
 * specific parts of the website.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Kris Wallsmith <kris@makhan.com>
 */
interface AccessMapInterface
{
    /**
     * Returns security attributes and required channel for the supplied request.
     *
     * @param Request $request The current request
     *
     * @return array A tuple of security attributes and the required channel
     */
    public function getPatterns(Request $request);
}
