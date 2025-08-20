<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect home ("/") to facilities list
Route::get('/', function () {
    return redirect()->route('facilities.index');
});

// Breeze auth routes (login, register, etc.)
require __DIR__.'/auth.php';

// Redirect dashboard (used by Breeze after login) → facilities index
Route::get('/dashboard', function () {
    return redirect()->route('facilities.index');
})->middleware(['auth'])->name('dashboard');

// Facilities CRUD (only for logged-in users)
Route::middleware(['auth'])->group(function () {
    Route::resource('facilities', FacilityController::class);

    // Export filtered facilities as CSV
    Route::get('facilities-export', [FacilityController::class, 'exportCsv'])
        ->name('facilities.export');
        Route::get('/facilities/{facility}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
Route::put('/facilities/{facility}', [FacilityController::class, 'update'])->name('facilities.update');

});
