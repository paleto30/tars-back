<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriasController extends Controller
{
    

    public static function obtenerCategorias()
    {   
        if (auth()->user()) {

            $categorias = Categorias::all()->values();
            if ($categorias->isEmpty()) {
                return response()->json([
                    "message" => "No existen registros",
                    "categorias" => $categorias
                ],200);
            }else {
                return response()->json([
                    'Categorias'=> $categorias
                ],200);
            }
        }
        return response()->json([
            'message' => 'No autorizado'
        ],401);
       

    }



    
    /* 
        esta funcion me va a permitir crear una nueva categoria 
    */
    public static function crearCategoria(Request $request)
    {   
        

        if (auth()->user()) {
            
            $catExistente = Categorias::where('nombre', $request->nombre)->value('id');

            if(! is_null($catExistente)){
                return response()->json([
                    'message' => 'La Categoria ya existe',
                ],200);
            }

            $validatedData = Validator::make($request->all(),[
                'nombre' => 'required|string|min:3',
                'descripcion' => 'required|string',
            ]);


            if ($validatedData->fails()) {
                return response()->json($validatedData->errors()->toJson(),400);
            }

            $newCategoria = new Categorias;
            $newCategoria->nombre =  $request->nombre;
            $newCategoria->descripcion = $request->descripcion;
            $newCategoria->created_by = auth()->id();

            if ($newCategoria->save()) {   
                return response()->json([
                    'message' => 'Categoria creada con exito!',
                    'data' => $newCategoria
                ],201);
            }

            return response()->json([
                'message' => 'Fallo la creacion de la categoria'
            ],401);

        }

        return response()->json([
            'message' => 'No autorizado'
        ],401);

    }



}
