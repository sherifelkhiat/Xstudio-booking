<?php

use Illuminate\Support\Facades\Route;
use Webkul\Xbooking\Http\Controllers\Shop\XbookingController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'xbooking'], function () {
    Route::get('xbooking/dates', [XbookingController::class, 'getDates'])->name('shop.xbooking.dates');
    Route::get('xbooking/times/{date}', [XbookingController::class, 'getTimes'])->name('shop.xbooking.times');
});