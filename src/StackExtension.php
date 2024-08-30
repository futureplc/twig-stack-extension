<?php

namespace Future\TwigStackExtension;

use Future\TwigStackExtension\TokenParser\PushOnceTokenParser;
use Future\TwigStackExtension\TokenParser\PushTokenParser;
use Future\TwigStackExtension\TokenParser\StackManager;
use Future\TwigStackExtension\TokenParser\StackTokenParser;
use Twig\Extension\AbstractExtension;

class StackExtension extends AbstractExtension
{
    protected StackManager $stackManager;

    public function __construct()
    {
        $this->stackManager = new StackManager();
    }

    public function getTokenParsers(): array
    {
        return [new PushTokenParser(), new PushOnceTokenParser(), new StackTokenParser()];
    }

    public function getStackManager(): StackManager
    {
        return $this->stackManager;
    }
}
