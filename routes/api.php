<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\TallaController;
use App\Http\Controllers\ZapatoController;
use App\Http\Controllers\ReservaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('categorias',CategoriaController::class);
Route::resource('marcas',MarcaController::class);
Route::resource('tallas',TallaController::class);
Route::resource('zapatos',ZapatoController::class);
Route::resource('reservas',ReservaController::class);
Route::put('/reservas/change',[ReservaController::class,'changeState']);
