<?php

namespace AmpedWeb\TwigStackExtension;

class Stack
{
    private string $name;

    private array $contents = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function appendContents(string $contents, bool $once = false): static
    {
        //Don't append any contents if we only want it once and it's already in the array.
        if ($once && in_array($contents, $this->contents)) {
            return $this;
        }

        $this->contents[] = $contents;

        return $this;
    }

    public function getContents(): string
    {
        return implode("\n", $this->contents);
    }
}
