<?php

namespace AmpedWeb\TwigStackExtension;

use Twig\TemplateWrapper;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;
use Twig\Error\RuntimeError;
use Twig\Environment as BaseEnvironment;

class Environment extends BaseEnvironment
{
    /**
     * @param string|TemplateWrapper $name The template name
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render($name, array $context = []): string
    {
        $html = parent::render($name, $context);

        if ($this->hasExtension(StackExtension::class)) {
            $stackManager = $this->getExtension(StackExtension::class)->getStackManager();
            $html = $stackManager->replaceStackPlaceholdersWithStackContent($html);
        }

        return $html;
    }
}
