<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tanzar\Conveyor\Helpers\Conveyor;
use Tanzar\Conveyor\Helpers\ConveyorUtils;

Route::get('conveyor/{key}', function (string $key, Request $request) {

    return Conveyor::get($key, $request->all() ?? []);
})->name('conveyor');


Route::get('conveyor/join/{key}', function (string $key, Request $request) {

    return [
        'channel' => 'conveyor.' . ConveyorUtils::formKey($key, $request->all() ?? []),
        'state' => Conveyor::get($key, $request->all() ?? [])
    ];
})->name('conveyor.join');