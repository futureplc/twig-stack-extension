<?php

namespace AmpedWeb\TwigStackExtension\Node;

use Twig\Node\Node;
use Twig\Node\CaptureNode;
use Twig\Attribute\YieldReady;
use AmpedWeb\TwigStackExtension\StackExtension;

#[YieldReady]
class PushNode extends Node
{
    public function __construct(string $stackName, Node $body, int $lineno = 0, string $tag = null, bool $pushOnce = false)
    {
        parent::__construct(['body'=>$body], ['name'=>$stackName, 'once'=>$pushOnce], $lineno, $tag);
    }

    public function compile(\Twig\Compiler $compiler)
    {
        $extension = StackExtension::class;
        $stackName = $this->getAttribute('name');
        $once = (bool) $this->getAttribute('once');

        $node = new CaptureNode($this->getNode('body'), $this->getNode('body')->lineno, $this->getNode('body')->tag);

        /**
         * The $tmp variable comes from the CaptureNode class, take a look at.
         * @see https://github.com/twigphp/Twig/blob/3.x/src/Node/CaptureNode.php#L35
         * this essentially contains the parsed and compiled output string.
         */
        $compiler
            ->subcompile($node)
            ->write("\$extension = \$this->env->getExtension('{$extension}');\n")
            ->write("\$stackManager = \$extension->getStackManager();\n\n")
            ->write("\$stackManager->appendContentOnStack('{$stackName}', \$tmp,{$once});\n\n");
    }
}
