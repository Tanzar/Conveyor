<?php

namespace Tanzar\Conveyor\Helpers;

use Illuminate\Support\Carbon;
use Tanzar\Conveyor\Core\ConveyorCore;
use Tanzar\Conveyor\Exceptions\UninitializedConveyorException;
use Tanzar\Conveyor\Models\ConveyorFrame;

final class ConveyorUtils
{

    public static function findFrame(string $baseKey, array $params): ConveyorFrame
    {
        $core = self::makeCore($baseKey);

        $initializer = $core->getInitializer();
        $valid = $initializer->checkValidity($params);
        $key = self::formKey($baseKey, $valid);

        $frame = ConveyorFrame::query()
            ->where('key', $key)
            ->first();

        if ($frame) {
            return $frame;
        }
        throw new UninitializedConveyorException($key);
    }

    public static function makeCore(string $baseKey): ConveyorCore
    {
        $class = config("conveyor.keys.$baseKey", $baseKey);

        return new $class($baseKey);
    }

    /**
     * Form key from params array
     * @param string[] $values
     * @return string
     */
    public static function formKey(string $baseKey, array $values): string
    {
        $text = "$baseKey-";
        foreach ($values as $key => $value) {
            if ($value instanceof Carbon) {
                $value = $value->format('Y-m-d-H-i-s');
            }
            $text .= "$key=$value;";
        }
        return $text;
    }

}
