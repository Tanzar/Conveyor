<?php

namespace Tanzar\Conveyor\Table\Frame;

class TableConfig
{
    private bool $hidden = false;
    private array $options = [];

    public function __construct(
        public readonly string $key,
        public readonly string $label
    ) {

    }

    final public function hide(bool $hide = true): self
    {
        $this->hidden = $hide;
        return $this;
    }

    final public function isHidden(): bool
    {
        return $this->hidden;
    }

    final public function option(string $key, mixed $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }

    final public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'options' => $this->options
        ];
    }

}
