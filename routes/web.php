<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tanzar\Conveyor\Helpers\Conveyor;
use Tanzar\Conveyor\Helpers\ConveyorUtils;

Route::get('conveyor/{key}', function (string $key, Request $request) {

    return Conveyor::get($key, $request->all() ?? [])->data();
})->name('conveyor');


Route::get('conveyor/join/{key}', function (string $key, Request $request) {

    $initializer = ConveyorUtils::makeCore($key)->getInitializer(false);

    return [
        'channel' => 'conveyor.' . $initializer->formKey($request->all() ?? []),
        'state' => Conveyor::get($key, $request->all() ?? [])->data()
    ];
})->name('conveyor.join');