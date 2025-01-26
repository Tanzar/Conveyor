<?php

namespace Tanzar\Conveyor\Base\Containers;

final class OrderedContainer
{
    private array $items = [];
    private array $order = [];

    /**
     * Adds item at the end of order list
     * if item key is already defined it will be ignored
     * 
     * @param string $key item key used to refference item
     * @param mixed $item value stored on list
     * @return void
     */
    public function add(string $key, mixed $item): void
    {
        if (isset($this->items[$key])) {
            return;
        }

        $this->items[$key] = $item;
        $this->order[] = $key;
    }

    /**
     * Adds item after given key
     * if key is not found item will be added at the end of list
     * if item key is already defined it will be ignored
     * 
     * @param string $key key name after which item will be added
     * @param string $itemKey item key used to refference item
     * @param mixed $item value stored on list
     * @return void
     */
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

    /**
     * Adds item before given key
     * if key is not found item will be added at the end of list
     * if item key is already defined it will be ignored
     * 
     * @param string $key key name before which item will be added
     * @param string $itemKey item key used to refference item
     * @param mixed $item value stored on list
     * @return void
     */
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

    /**
     * Returns item for given key
     * if key is not set returns null
     * 
     * @param string $key searched key
     * @return mixed item assigned to key, null if key not found
     */
    public function get(string $key): mixed
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }
        return null;
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
