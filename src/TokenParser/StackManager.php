<?php

namespace AmpedWeb\TwigStackExtension\TokenParser;

use AmpedWeb\TwigStackExtension\Stack;

class StackManager
{
    private array $stacks = [];

    private function getStackByName(string $name): Stack
    {
        if (!isset($this->stacks[$name])) {
            $this->stacks[$name] = new Stack($name);
        }

        return $this->stacks[$name];
    }

    public function makeStackPlaceHolder(string $name): string
    {
        $stack = $this->getStackByName($name);

        return '<template data-stack-placeholder="' . $stack->getName() . '"></template>';
    }

    public function replaceStackPlaceholdersWithStackContent(string $contents): string
    {
        $regex = '/<template data-stack-placeholder="(.+)"><\/template>/';

        return preg_replace_callback($regex, function ($matches) {
            $stack = $this->getStackByName($matches[1]);

            return $stack->getContents();
        }, $contents);
    }

    public function appendContentOnStack(string $name, string $contents, bool $once = false): void
    {
        $stack = $this->getStackByName($name);
        $stack->appendContents($contents, $once);
    }
}
