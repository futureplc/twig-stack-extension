<?php

namespace AmpedWeb\TwigStackExtension\Node;

use Twig\Compiler;
use Twig\Node\Node;
use Twig\Attribute\YieldReady;
use AmpedWeb\TwigStackExtension\StackExtension;

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
