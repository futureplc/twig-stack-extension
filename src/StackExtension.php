<?php

namespace AmpedWeb\TwigStackExtension;

use AmpedWeb\TwigStackExtension\TokenParser\PushOnceTokenParser;
use AmpedWeb\TwigStackExtension\TokenParser\PushTokenParser;
use AmpedWeb\TwigStackExtension\TokenParser\StackManager;
use AmpedWeb\TwigStackExtension\TokenParser\StackTokenParser;
use Twig\Extension\AbstractExtension;

class StackExtension extends AbstractExtension
{
    public function __construct(protected StackManager $stackManager) {}

    public function getTokenParsers(): array
    {
        return [new PushTokenParser, new PushOnceTokenParser, new StackTokenParser];
    }

    public function getStackManager(): StackManager
    {
        return $this->stackManager;
    }
}
