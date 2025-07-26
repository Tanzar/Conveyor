<?php

namespace Tanzar\Conveyor\Helpers;

use Tanzar\Conveyor\Exceptions\UnauthorizedAccessException;

final class Conveyor
{

    public static function init(string $class): ConveyorInitHelper
    {
        return new ConveyorInitHelper($class);
    }

    public static function update(): ConveyorUpdateHelper
    {
        return new ConveyorUpdateHelper();
    }

    public static function updateByClass(string $class): ConveyorUpdateByKeyHelper
    {
        return new ConveyorUpdateByKeyHelper($class);
    }

    public static function get(string $class, array $params = []): array
    {
        $core = ConveyorUtils::makeCore($class);

        $frame = ConveyorUtils::findFrame($class, $params);

        if (!$core->allowAccess($frame)) {
            throw new UnauthorizedAccessException($class);
        }
        
        return $core->formatData($frame);
    }
}
