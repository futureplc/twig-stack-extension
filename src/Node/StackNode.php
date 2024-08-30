<?php

namespace AmpedWeb\TwigStackExtension\Node;

use AmpedWeb\TwigStackExtension\StackExtension;
use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

#[YieldReady]
class StackNode extends Node
{
    /**
     * new StackNode().
     */
    public function __construct(string $name, int $lineno = 0, string $tag = null)
    {
        parent::__construct([], ['name' => $name], $lineno, $tag);
    }

    /**
     * StackNode->compile().
     */
    public function compile(Compiler $compiler)
    {
        $extension = $compiler->getEnvironment()->getExtension(StackExtension::class);
        $manager = $extension->getStackManager();

        $stackPlaceHolder = $manager->makeStackPlaceHolder($this->getAttribute('name'));
        $compiler->write("yield '{$stackPlaceHolder}';\n");
    }
}
