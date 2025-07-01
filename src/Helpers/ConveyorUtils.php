<?php

namespace Tanzar\Conveyor\Helpers;

use Tanzar\Conveyor\Core\ConveyorCore;
use Tanzar\Conveyor\Exceptions\UninitializedConveyorException;
use Tanzar\Conveyor\Models\ConveyorFrame;

final class ConveyorUtils
{

    public static function findFrame(string $baseKey, array $params): ConveyorFrame
    {
        $core = self::makeCore($baseKey);

        $initializer = $core->getInitializer();
        $initializer->checkValidity($params);
        $key = $baseKey . '-' . $initializer->formKey($params);

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

}
