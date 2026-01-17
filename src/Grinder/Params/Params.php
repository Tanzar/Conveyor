<?php

namespace Tanzar\Conveyor\Grinder\Params;

use Illuminate\Support\Carbon;

interface Params
{
    public function string(string $key, string $value): Params;

    public function int(string $key, int $value): Params;

    public function float(string $key, float $value): Params;

    public function date(string $key, Carbon $value, string $format = 'Y-m-d'): Params;

}
