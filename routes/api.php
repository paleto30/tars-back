<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Tymon\JWTAuth\Http\Middleware\Check;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\PublicacionesController;
use App\Http\Middleware\JwtMiddleware;

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
    //Route::get('users', [UserController::class,'perfil']);
    //rutas de categoria
    Route::get('categoria/',[CategoriasController::class,'obtenerCategorias']);
    Route::post('categoria/add',[CategoriasController::class,'crearCategoria']);


    //rutas de publicaciones 
    Route::post('publicacion/crear',[PublicacionesController::class,'CrearPublicacion']);


    //ruta para obtener las publicaciones del usuario en cuestion

});



Route::get('auth/user/list-document', [PublicacionesController::class,'listarPublicacionesPerfilUser'])->middleware(JwtMiddleware::class);


Route::middleware('jwt.verify')->get('users',[UserController::class,'perfil']);



