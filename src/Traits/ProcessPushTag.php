<?php

namespace AmpedWeb\TwigStackExtension\Traits;

use AmpedWeb\TwigStackExtension\Node\PushNode;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Parser;
use Twig\Token;

trait ProcessPushTag
{
    protected function makePushNode(string $tag, Token $token, Parser $parser, string $endTokenName = 'endpush', bool $pushOnce = false): PushNode
    {
        $stream = $parser->getStream();
        $lineNo = $token->getLine();

        /**
         * Remember -
         * expect() - moves the current token pointer on if the condition in valid and throws if not.
         * nextIf() - moves the current token pointer on if the condition is valid, and will throw if there are no more tokens.
         */

        //Confirm we have a stack name and throw a syntax error if not.
        $stackNameToken = $stream->expect(Token::NAME_TYPE, message: "Expected $tag to define a stack name");
        $stackName = $stackNameToken->getValue();

        $parser->pushLocalScope();

        //Move our pointer along if the next node is '%}'
        if ($stream->nextIf(Token::BLOCK_END_TYPE)) {
            // create subtree until the passed closure returns true. This is the body we want to 'push' to the 'stack'
            $pushBlockBody = $parser->subparse(function (Token $token) use ($endTokenName) {
                //In this case we're testing for the tokens 'endpush' or 'endpushonce'
                return $token->test($endTokenName);
            }, true);
        } else {
            $pushBlockBody = new Node([
                new PrintNode($parser->getExpressionParser()->parseExpression(), $lineNo),
            ]);
        }
        $parser->popLocalScope();

        //Confirm we have gotten to the "%}" of our endpush/endpushonce block and move the pointer
        $stream->expect(Token::BLOCK_END_TYPE);

        return new PushNode($stackName, $pushBlockBody, $lineNo, $tag, $pushOnce);
    }
}
