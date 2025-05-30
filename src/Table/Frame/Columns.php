<?php

namespace Tanzar\Conveyor\Table\Frame;


class Columns
{
    private OrderedContainer $items;

    public function __construct()
    {
        $this->items = new OrderedContainer();
    }

    /**
     * Adds new column at end or list
     * 
     * @param string $key new column key name, used to reference column
     * @param string $label
     * @return Column
     */
    public function add(string $key, string $label): Column
    {
        $column = new Column($key, $label);
        $this->items->add($key, $column);
        return $column;
    }

    /**
     * Adds new column after given key
     * if given key is not set new column will be added at the end
     * 
     * @param string $key column key after which new column will be added
     * @param string $itemKey new column key name, used to reference column
     * @param string $label 
     * @return Column
     */
    public function after(string $key, string $itemKey, string $label): Column
    {
        $column = new Column($itemKey, $label);
        $this->items->addAfter($key, $itemKey, $column);
        return $column;
    }

    /**
     * Adds new column before given key
     * if given key is not set new column will be added at the end
     * 
     * @param string $key column key before which new column will be added
     * @param string $itemKey new column key name, used to reference column
     * @param string $label 
     * @return Column
     */
    public function before(string $key, string $itemKey, string $label): Column
    {
        $column = new Column($itemKey, $label);
        $this->items->addBefore($key, $itemKey, $column);
        return $column;
    }

    /**
     * Retrieves column by key
     * If column is not found returns null
     * 
     * @param string $key key of column to retrieve 
     * @return Column|null retrieved column, null if not found
     */
    public function get(string $key): ?Column
    {
        return $this->items->get($key);
    }

    public function isSet(string $key): bool
    {
        return $this->items->have($key);
    }

    /**
     * calling handle on every column
     * by default ignores hidden column
     * 
     * @param callable $handle code run for each column, passes column as parmeters
     * @param bool $withHiden include hidden column
     * @return void
     */
    public function each(callable $handle, bool $withHiden = false): void
    {
        /** @var Column $item */
        foreach ($this->items->toArray() as $item) {
            if ($withHiden || !$item->isHidden()) {
                $handle($item);
            }
        }
    }

    /**
     * Turns columns into array, skips hidden columns
     * @return array[]
     */
    public function toArray(): array
    {
        $result = [];
        /** @var Column $item */
        foreach ($this->items->toArray() as $item) {
            if (!$item->isHidden()) {
                $result[] = $item->toArray();
            }
        }
        return $result;
    }
}
