<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection;

use Makhan\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;
use Makhan\Component\ExpressionLanguage\ParserCache\ParserCacheInterface;

/**
 * Adds some function to the default ExpressionLanguage.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 *
 * @see ExpressionLanguageProvider
 */
class ExpressionLanguage extends BaseExpressionLanguage
{
    public function __construct(ParserCacheInterface $cache = null, array $providers = array())
    {
        // prepend the default provider to let users override it easily
        array_unshift($providers, new ExpressionLanguageProvider());

        parent::__construct($cache, $providers);
    }
}
