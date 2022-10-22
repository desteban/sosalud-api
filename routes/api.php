<?php

use App\Http\Controllers\comprimidosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/comprimidos', [comprimidosController::class, 'crearRIPS'])->name('comprimidos.guardar')
    ->middleware('aut');
Route::post('/registrar', [RegistroController::class, 'registrarUsuario'])->name('usuario.crear');
Route::post('/login', [LoginController::class, 'login'])->name('usuario.login');
