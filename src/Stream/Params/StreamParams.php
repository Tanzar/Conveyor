<?php


namespace Tanzar\Conveyor\Stream\Params;

use Illuminate\Support\Carbon;

class StreamParams
{
	public function __construct(private array $values)
    {
        
    }

    public function int(string $key, int $default = 0): int
    {
        return $this->values[$key] ?? $default;
    }

    public function string(string $key, string $default = ''): string
    {
        return $this->values[$key] ?? $default;
    }

    public function date(string $key, ?Carbon $default = null): Carbon
    {
        $value = $this->values[$key] ?? null;
        if ($value) {
            if ($value instanceof Carbon) {
                return $value;
            }
            return Carbon::parse($value);
        }
        return $default ?? now();
    }

    public function toArray(): array
    {
        return $this->values;
    }

}