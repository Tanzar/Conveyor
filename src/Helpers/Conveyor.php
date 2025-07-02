<?php

namespace Tanzar\Conveyor\Helpers;

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
}
