<?php

namespace Tanzar\Conveyor\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Tanzar\Conveyor\Helpers\Conveyor;

trait UseConveyor
{
    public static function bootUseConveyor(): void
    {
        static::registerEvents([
            'created',
            'updated',
            'deleted',
            'forceDeleted',
            'restored'
        ]);
    }

    /**
     * Registers given listeners
     * @param string[] $event
     * @return void
     */
    private static function registerEvents(array $events): void
    {
        foreach ($events as $event) {
            if (method_exists(self::class, $event)) {
                static::$event(function (Model $model) {

                    Conveyor::update()
                        ->model($model, static::$dispatchConveyor ?? true);
                });
            }

        }
    }
}
