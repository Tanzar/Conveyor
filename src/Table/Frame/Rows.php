<?php

namespace Tanzar\Conveyor\Table\Frame;

use Tanzar\Conveyor\Base\Containers\OrderedContainer;

final class Rows
{
    private OrderedContainer $items;

    public function __construct()
    {
        $this->items = new OrderedContainer();
    }

    /**
     * Adds new row at end or list
     * 
     * @param string $key new row key name, used to reference row
     * @param string $label
     * @return Row
     */
    public function add(string $key, string $label): Row
    {
        $row = new Row($key, $label);
        $this->items->add($key, $row);
        return $row;
    }

    /**
     * Adds new row after given key
     * if given key is not set new row will be added at the end
     * 
     * @param string $key row key after which new row will be added
     * @param string $itemKey new row key name, used to reference row
     * @param string $label 
     * @return Row
     */
    public function after(string $key, string $itemKey, string $label): Row
    {
        $row = new Row($itemKey, $label);
        $this->items->addAfter($key, $itemKey, $row);
        return $row;
    }

    /**
     * Adds new row before given key
     * if given key is not set new row will be added at the end
     * 
     * @param string $key row key before which new row will be added
     * @param string $itemKey new row key name, used to reference row
     * @param string $label 
     * @return Row
     */
    public function before(string $key, string $itemKey, string $label): Row
    {
        $row = new Row($itemKey, $label);
        $this->items->addBefore($key, $itemKey, $row);
        return $row;
    }

    /**
     * Retrieves row by key
     * If row is not found returns null
     * 
     * @param string $key key of row to retrieve 
     * @return Row|null retrieved row, null if not found
     */
    public function get(string $key): ?Row
    {
        return $this->items->get($key);
    }

    /**
     * calling handle on every row
     * by default ignores hidden rows
     * 
     * @param callable $handle code run for each row, passes row as parmeters
     * @param bool $withHiden include hidden rows
     * @return void
     */
    public function each(callable $handle, bool $withHiden = false): void
    {
        /** @var Row $item */
        foreach ($this->items->toArray() as $item) {
            if ($withHiden || !$item->isHidden()) {
                $handle($item);
            }
        }
    }

    /**
     * Turns rows into array, skips hidden rows
     * @return array[]
     */
    public function toArray(): array
    {
        $result = [];
        /** @var Row $item */
        foreach ($this->items->toArray() as $item) {
            if (!$item->isHidden()) {
                $result[] = $item->toArray();
            }
        }
        return $result;
    }
}
