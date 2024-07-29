<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DefectController;

Route::get('/', function () {
    return redirect()->route('defects.index');
});

Auth::routes();

Route::resource('defects', DefectController::class);

// web.php
Route::delete('/defects/{defect}/remove-image/{image}', [DefectController::class, 'removeImage'])->name('defects.removeImage');

Route::get('/dash', [DefectController::class, 'dashboard'])->name('dash');