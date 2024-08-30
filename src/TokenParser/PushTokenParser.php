<?php

namespace AmpedWeb\TwigStackExtension\TokenParser;

use Twig\Token;
use Twig\Error\SyntaxError;
use Twig\TokenParser\AbstractTokenParser;
use AmpedWeb\TwigStackExtension\Node\PushNode;
use AmpedWeb\TwigStackExtension\Traits\ProcessPushTag;

class PushTokenParser extends AbstractTokenParser
{
    use ProcessPushTag;

    public function parse(Token $token): PushNode
    {
        return $this->makePushNode($this->getTag(), $token, $this->parser, pushOnce: false);
    }

    public function getTag(): string
    {
        return 'push';
    }
}
