<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\formularioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function ()
{
    return view('formulario');
});

Route::post('/archivos', [formularioController::class, 'guardarArchivo'])->name('archivos.store');
Route::get('/registro', function ()
{
    return view('registro');
});
