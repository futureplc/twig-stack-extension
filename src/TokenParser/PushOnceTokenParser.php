<?php

namespace AmpedWeb\TwigStackExtension\TokenParser;

use AmpedWeb\TwigStackExtension\Node\PushNode;
use AmpedWeb\TwigStackExtension\Traits\ProcessPushTag;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class PushOnceTokenParser extends AbstractTokenParser
{
    use ProcessPushTag;

    public function parse(Token $token): PushNode
    {
        return $this->makePushNode($this->getTag(), $token, $this->parser, endTokenName: 'endpushonce', pushOnce: true);
    }

    public function getTag(): string
    {
        return 'pushonce';
    }
}
