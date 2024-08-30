<?php

namespace AmpedWeb\TwigStackExtension\TokenParser;

use Twig\Token;
use Twig\Error\SyntaxError;
use Twig\TokenParser\AbstractTokenParser;
use AmpedWeb\TwigStackExtension\Node\PushNode;
use AmpedWeb\TwigStackExtension\Traits\ProcessPushTag;

class PushOnceTokenParser extends AbstractTokenParser
{
    use ProcessPushTag;

    public function parse(Token $token): PushNode
    {
        return $this->makePushNode($this->getTag(), $token, $this->parser, endTokenName:'endpushonce', pushOnce: true);
    }

    public function getTag(): string
    {
        return 'pushonce';
    }
}
