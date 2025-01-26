<?php

namespace Tanzar\Conveyor\Base\Containers;

class OrderedContainer
{
    private array $items = [];
    private array $order = [];

    public function add(string $key, mixed $item): void
    {
        if (isset($this->items[$key])) {
            return;
        }

        $this->items[$key] = $item;
        $this->order[] = $key;
    }

    public function addAfter(string $key, string $itemKey, mixed $item): void
    {
        if (isset($this->items[$itemKey])) {
            return;
        }

        $newOrder = [];
        $added = false;
        foreach ($this->order as $current) {
            $newOrder[] = $current;
            if ($current === $key) {
                $newOrder[] = $itemKey;
                $added = true;
            }
        }

        if (!$added) {
            $newOrder[] = $itemKey;
        }
        $this->items[$itemKey] = $item;
        $this->order = $newOrder;
    }

    public function addBefore(string $key, string $itemKey, mixed $item): void
    {
        if (isset($this->items[$itemKey])) {
            return;
        }

        $newOrder = [];
        $added = false;
        foreach ($this->order as $current) {
            if ($current === $key) {
                $newOrder[] = $itemKey;
                $added = true;
            }
            $newOrder[] = $current;
        }

        if (!$added) {
            $newOrder[] = $itemKey;
        }
        $this->items[$itemKey] = $item;
        $this->order = $newOrder;
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->order as $key) {
            $result[] = $this->items[$key];
        }
        return $result;
    }
}
