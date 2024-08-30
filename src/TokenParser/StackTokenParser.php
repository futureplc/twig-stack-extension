<?php

namespace AmpedWeb\TwigStackExtension\TokenParser;

use AmpedWeb\TwigStackExtension\Node\StackNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * StackTokenParser.
 *
 * Declares the tag `{% stack %}`.
 */
class StackTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): StackNode
    {
        $stream = $this->parser->getStream();

        $name = $stream->expect(Token::NAME_TYPE)->getValue();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new StackNode($name, $token->getLine());
    }

    /**
     * {@inheritDoc}
     */
    public function getTag()
    {
        return 'stack';
    }
}
