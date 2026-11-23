<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudPracticaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/solicitudes', [SolicitudPracticaController::class, 'index'])
    ->name('solicitudes.index');

Route::get('/crear/solicitud', [SolicitudPracticaController::class, 'create'])
    ->name('solicitudes.create');

Route::post('/solicitudes', [SolicitudPracticaController::class, 'store'])
    ->name('solicitudes.store');

Route::get('/solicitudes/{id}', [SolicitudPracticaController::class, 'show'])
    ->name('solicitudes.show');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');



