<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tanzar\Conveyor\Helpers\Conveyor;

Route::get('conveyor/{key}', function (string $key, Request $request) {

    return Conveyor::get($key, $request->all());
})->name('conveyor');