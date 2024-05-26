<?php

use Illuminate\Support\Facades\Route;
use Webkul\Xbooking\Http\Controllers\Admin\XbookingController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/xbooking'], function () {
    Route::controller(XbookingController::class)->group(function () {
        Route::get('', 'index')->name('admin.xbooking.index');
    });
});