<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\CssSelector\Parser\Handler;

use Makhan\Component\CssSelector\Parser\Reader;
use Makhan\Component\CssSelector\Parser\Token;
use Makhan\Component\CssSelector\Parser\TokenStream;

/**
 * CSS selector whitespace handler.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class WhitespaceHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(Reader $reader, TokenStream $stream)
    {
        $match = $reader->findPattern('~^[ \t\r\n\f]+~');

        if (false === $match) {
            return false;
        }

        $stream->push(new Token(Token::TYPE_WHITESPACE, $match[0], $reader->getPosition()));
        $reader->moveForward(strlen($match[0]));

        return true;
    }
}
