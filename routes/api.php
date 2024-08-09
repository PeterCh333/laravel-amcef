<?php

use App\Http\Controllers\DataExportController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//normalne by sa user vytvaral cez autentifikaciu ale tú teraz nejdem robiť

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('api.user.index');
    Route::get('/{id}', [UserController::class, 'show'])->name('api.user.show');
    Route::post('/', [UserController::class, 'store'])->name('api.user.store');
    Route::put('/{id}', [UserController::class, 'update'])->name('api.user.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.user.destroy');
});

Route::prefix('document')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('api.document.index');
    Route::get('/{id}', [DocumentController::class, 'show'])->name('api.document.show');
    Route::post('/', [DocumentController::class, 'store'])->name('api.document.store');
    Route::put('/{id}', [DocumentController::class, 'update'])->name('api.document.update');
    Route::delete('/{id}', [DocumentController::class, 'destroy'])->name('api.document.destroy');
});

Route::get('export/xlsx', [DataExportController::class, 'exportToXlsx']);
Route::get('export/pdf', [DataExportController::class, 'exportToPdf']);
Route::get('export/csv', [DataExportController::class, 'exportToCsv']);
