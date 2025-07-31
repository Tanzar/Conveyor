<?php

namespace Tanzar\Conveyor\Cells;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Core\ConveyorCore;
use Tanzar\Conveyor\Exceptions\CellLockedException;
use Tanzar\Conveyor\Exceptions\CellValueException;
use Tanzar\Conveyor\Exceptions\InvalidCallException;
use Tanzar\Conveyor\Models\ConveyorCell;

/**
 * Basic class for containing values
 */
final class Cell implements CellInterface
{
    private ?Closure $reactive = null;
    private bool $locked = false;

    public static ?Model $currentModel = null;
    private ?Model $lastAccessed = null;

    public function __construct(private ConveyorCell $cell)
    {

    }


    /**
     * sets if cell will be shown in resulting json (true / false), and 
     * @param ?bool $hide sets if cell should be shown in formatters, null skips this options
     * @return bool current cell state
     */
    public function hidden(?bool $hide = null): bool
    {
        if ($hide !== null) {
            $this->cell->hidden = $hide;
        }
        return $this->cell->hidden;
    }

    /**
     * Sets cell value to be calcualted after processing all models
     * if null is passed as parameter cell will behave like normal
     * @param mixed $handle
     * @return void
     */
    public function setReactive(?callable $handle): void
    {
        $this->reactive = $handle;
    }

    /**
     * Set option value for cell
     * @param string $key
     * @param bool|string|float $value
     * @return void
     */
    public function setOption(string $key, bool|string|float $value): void
    {
        $options = $this->cell->options;
        $options[$key] = $value;
        $this->cell->options = $options;
    }

    /**
     * Get option value casted to boolean
     * @param string $key
     * @return bool
     */
    public function getOptionAsBool(string $key): bool
    {
        return (bool) $this->cell->options[$key];
    }
    
    /**
     * Get option value casted to string
     * @param string $key
     * @return string
     */
    public function getOptionAsString(string $key): string
    {
        return (string) $this->cell->options[$key];
    }
    
    /**
     * Get option value casted to float
     * @param string $key
     * @return float
     */
    public function getOptionAsFloat(string $key): float
    {
        return (float) $this->cell->options[$key];
    }
    
    /**
     * Changes cell value by given number
     * @param float $value - number by which value is changed
     * @throws InvalidCallException
     * @return void
     */
    public function change(float $value): void
    {
        if (self::$currentModel === null) {
            throw new InvalidCallException('Invalid call to cell method change(), call only inside handlers of ' .
             ConveyorCore::class . ' implementations.');
        }

        if (self::$currentModel !== $this->lastAccessed) {
            $this->setLastAccessed();
        }
        
        $this->updateValue($value);
    }

    private function setLastAccessed(): void
    {
        $this->lastAccessed = self::$currentModel;
        $values = $this->cell->models[self::$currentModel::class] ?? [];
        $value = $values[self::$currentModel->getKey()] ?? 0;

        $this->cell->value -= $value;
        $values[self::$currentModel->getKey()] = 0;
        $models = $this->cell->models;
        $models[self::$currentModel::class] = $values;
        $this->cell->models = $models;
    }

    private function updateValue(float $value): void
    {
        $this->cell->value += $value;

        $class = self::$currentModel::class;

        $models = $this->cell->models;
        
        if (!isset($models[$class])) {
            $models[$class] = [];
        }

        $id = self::$currentModel->getKey();


        if (isset($models[$class][$id])) {
            $models[$class][$id] += $value;
        } else {
            $models[$class] = [ $id => $value ];
        }

        $this->cell->models = $models;
    }

    /**
     * Remove given model from cell
     * @param Model $model
     * @return void
     */
    public function removeModel(Model $model): void
    {
        if (self::$currentModel === $this->lastAccessed) {
            return;
        }

        $models = $this->cell->models;

        $isset = isset(
            $models[$model::class],
            $models[$model::class][$model->getKey()]
        );

        if ($isset) {
            $value = $models[$model::class][$model->getKey()];

            $this->cell->value -= $value;
            unset($models[$model::class][$model->getKey()]);
            $this->cell->models = $models;
        }
    }

    /**
     * Returns cell current value
     * @return float|int
     */
    public function getValue(): float
    {
        if ($this->reactive) {
            return $this->getReactive();
        }
        return $this->cell->value ?? 0;
    }

    private function getReactive(): float
    {
        if ($this->locked) {
            throw new CellLockedException();
        }
        $this->locked = true;
        $calculate = $this->reactive;
        $value = $calculate() ?? 'error';
        if (!is_float($value) && !is_int($value)) {
            throw new CellValueException('Incorrect cell value returned, must return float');
        }
        $this->locked = false;
        return $value;
    }

    /**
     * Save cell state
     * @return void
     */
    public function save(): void
    {
        if ($this->reactive) {
            if ($this->cell->exists()) {
                $this->cell->delete();
            }
            return;
        }

        if ($this->cell->isDirty() || $this->cell->doesntExist()) {
            $this->cell->save();
        }
    }

    public function getModels(): array
    {
        return $this->cell->models;
    }
}
