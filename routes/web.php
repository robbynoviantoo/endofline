<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DefectController;

Route::get('/', function () {
    return redirect()->route('defects.index');
});

Auth::routes();

Route::resource('defects', DefectController::class);

Route::delete('/defects/{defect}/remove-image/{image}', [DefectController::class, 'removeImage'])->name('defects.removeImage');

Route::get('/dashboard', [DefectController::class, 'dashboard'])->name('dash');
Route::get('/defects', [DefectController::class, 'index'])->name('defects.index');
Route::get('/filter-cell', [DefectController::class, 'index'])->name('filter.cell');