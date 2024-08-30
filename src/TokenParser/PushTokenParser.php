<?php

namespace Future\TwigStackExtension\TokenParser;

use Future\TwigStackExtension\Node\PushNode;
use Future\TwigStackExtension\Traits\ProcessPushTag;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

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
