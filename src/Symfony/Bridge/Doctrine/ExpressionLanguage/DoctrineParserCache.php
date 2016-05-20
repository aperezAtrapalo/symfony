<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\ExpressionLanguage;

use Doctrine\Common\Cache\Cache;
use Makhan\Component\ExpressionLanguage\ParsedExpression;
use Makhan\Component\ExpressionLanguage\ParserCache\ParserCacheInterface;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class DoctrineParserCache implements ParserCacheInterface
{
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        if (false === $value = $this->cache->fetch($key)) {
            return;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function save($key, ParsedExpression $expression)
    {
        $this->cache->save($key, $expression);
    }
}
