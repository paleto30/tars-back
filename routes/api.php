<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Tymon\JWTAuth\Http\Middleware\Check;
use App\Http\Controllers\CategoriasController;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function($router){
    Route::get('users', [UserController::class,'perfil']);
});


Route::get('categoria/',[CategoriasController::class,'obtenerCategorias']);
Route::post('categoria/add',[CategoriasController::class,'crearCategoria']);

