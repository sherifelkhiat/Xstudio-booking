<?php

use Illuminate\Support\Facades\Route;
use Webkul\Xbooking\Http\Controllers\Admin\WorkingDaysController;
use Webkul\Xbooking\Http\Controllers\Admin\XbookingController;
use Webkul\Xbooking\Http\Controllers\Admin\CitiesController;


Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/xbooking'], function () {
    Route::controller(XbookingController::class)->group(function () {
        Route::get('', 'index')->name('admin.xbooking.index');
    });

    Route::get('working-days', [WorkingDaysController::class , 'index'])->name('xbooking.working_days.index');
    Route::get('working-days/alldata', [WorkingDaysController::class , 'alldata'])->name('xbooking.working_days.alldata');
    Route::get('working-days/create', [WorkingDaysController::class, 'create'])->name('xbooking.working_days.create');
    Route::post('working-days/store', [WorkingDaysController::class, 'store'])->name('xbooking.working_days.store');
    Route::get('working-days/{id}/edit', [WorkingDaysController::class, 'edit'])->name('xbooking.working_days.edit');
    Route::put('working-days/{id}/update', [WorkingDaysController::class, 'update'])->name('xbooking.working_days.update');
    Route::delete('working-days/{id}/delete', [WorkingDaysController::class, 'delete'])->name('xbooking.working_days.delete');


    //cities routs
    Route::get('/cities', [CitiesController::class, 'index'])->name('xbooking.cities.index');
    Route::get('/cities/create', [CitiesController::class, 'create'])->name('xbooking.cities.create');
    Route::post('/cities/store', [CitiesController::class, 'store'])->name('xbooking.cities.store');
    Route::get('/cities/edit/{id}', [CitiesController::class, 'edit'])->name('xbooking.cities.edit');
    Route::put('/cities/update/{id}', [CitiesController::class, 'update'])->name('xbooking.cities.update');
    Route::delete('/cities/delete/{id}', [CitiesController::class, 'delete'])->name('xbooking.cities.delete');
    //Route::delete('/xbooking/cities/delete/{id}', [CitiesController::class, 'delete'])->name('xbooking.cities.delete');

    Route::group(['prefix' => '/exception_days'], function () {
        Route::get('/', 'Webkul\Xbooking\Http\Controllers\Admin\ExceptionDayController@index')->name('xbooking.exception_days.index');
        Route::get('/create', 'Webkul\Xbooking\Http\Controllers\Admin\ExceptionDayController@create')->name('xbooking.exception_days.create');
        Route::post('/', 'Webkul\Xbooking\Http\Controllers\Admin\ExceptionDayController@store')->name('xbooking.exception_days.store');
        Route::get('/{id}/edit', 'Webkul\Xbooking\Http\Controllers\Admin\ExceptionDayController@edit')->name('xbooking.exception_days.edit');
        Route::put('/{id}', 'Webkul\Xbooking\Http\Controllers\Admin\ExceptionDayController@update')->name('xbooking.exception_days.update');
        Route::delete('/{id}', 'Webkul\Xbooking\Http\Controllers\Admin\ExceptionDayController@delete')->name('xbooking.exception_days.destroy'); // Use "destroy" for consistency with Blade method
    });

});