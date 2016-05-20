<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\CssSelector\Tests\Parser\Handler;

use Makhan\Component\CssSelector\Parser\Handler\IdentifierHandler;
use Makhan\Component\CssSelector\Parser\Token;
use Makhan\Component\CssSelector\Parser\Tokenizer\TokenizerPatterns;
use Makhan\Component\CssSelector\Parser\Tokenizer\TokenizerEscaping;

class IdentifierHandlerTest extends AbstractHandlerTest
{
    public function getHandleValueTestData()
    {
        return array(
            array('foo', new Token(Token::TYPE_IDENTIFIER, 'foo', 0), ''),
            array('foo|bar', new Token(Token::TYPE_IDENTIFIER, 'foo', 0), '|bar'),
            array('foo.class', new Token(Token::TYPE_IDENTIFIER, 'foo', 0), '.class'),
            array('foo[attr]', new Token(Token::TYPE_IDENTIFIER, 'foo', 0), '[attr]'),
            array('foo bar', new Token(Token::TYPE_IDENTIFIER, 'foo', 0), ' bar'),
        );
    }

    public function getDontHandleValueTestData()
    {
        return array(
            array('>'),
            array('+'),
            array(' '),
            array('*|foo'),
            array('/* comment */'),
        );
    }

    protected function generateHandler()
    {
        $patterns = new TokenizerPatterns();

        return new IdentifierHandler($patterns, new TokenizerEscaping($patterns));
    }
}
