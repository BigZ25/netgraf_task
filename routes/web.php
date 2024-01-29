<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/pets');
});

Route::get('/pets', [PetController::class, 'index']);
Route::get('/pets/create', [PetController::class, 'create']);
Route::get('/pets/{petId}', [PetController::class, 'show']);
Route::get('/pets/{petId}/edit', [PetController::class, 'edit']);
Route::get('/pets/{petId}/destroy', [PetController::class, 'destroy']);
Route::put('/pets/{petId}', [PetController::class, 'update']);
Route::post('/pets', [PetController::class, 'store']);
