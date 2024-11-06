<?php

    use App\Http\Controllers\AnggotaController;
    use Illuminate\Support\Facades\Route;

    Route::get('/', [AnggotaController::class, 'index'])->name('index');
    Route::post('/', [AnggotaController::class, 'store'])->name('store');
    Route::delete('/{id}', [AnggotaController::class, 'destroy'])->name('destroy');
