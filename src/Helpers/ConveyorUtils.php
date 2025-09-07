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

        $initializer = $core->getInitializer(false);
        $valid = $initializer->checkValidity($params);
        $key = $initializer->formKey($valid);

        $frame = ConveyorFrame::query()
            ->where('key', $key)
            ->first();

        if ($frame) {
            return $frame;
        }

        if (config('conveyor.autoInit')) {
            
            $frame = new ConveyorFrame();
            $frame->key = $key;
            $frame->base_key = $baseKey;
            $frame->params = $valid;
            $frame->save();

            return $frame;
        }

        throw new UninitializedConveyorException($key);
    }

    public static function init(string $baseKey, array $params): void
    {
        $core = self::makeCore($baseKey);

        $initializer = $core->getInitializer(true);

        $valid = $initializer->checkValidity($params);

        $key =  $initializer->formKey($valid);

        $doesntExist = ConveyorFrame::query()
            ->where('key', $key)
            ->doesntExist();

        if ($doesntExist) {
            $frame = new ConveyorFrame();
            $frame->key = $key;
            $frame->base_key = $baseKey;
            $frame->params = $valid;
            $frame->save();
        }
    }

    public static function makeCore(string $baseKey): ConveyorCore
    {
        $class = config("conveyor.keys.$baseKey", $baseKey);

        return new $class($baseKey);
    }
}
