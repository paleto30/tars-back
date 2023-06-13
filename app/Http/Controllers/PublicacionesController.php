<?php

namespace App\Http\Controllers;

use App\Models\Documentaciones;
use App\Models\Publicaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublicacionesController extends Controller
{

    /* 
        para crear una publicacion yo necesito estar logueado como un usuario
        e ir al apartado de publicar documentacion
        
        en este apartado se va a cargar un formulario en el que podre ingresar

        de la documentacion :
        TITULO -> nombre descriptivo y claro
        CONTENIDO -> texto en el que se describe el contenido del documento
        URL_ARCHIVO -> aqui se debe cargar el archivo
        ID_CATEGORIA -> se debe selecionar una de las categorias disponibles
        CREATED_BY -> id del usuario activo en la sesion         
    */

    public function CrearPublicacion(Request $request)
    {

            $validator = Validator::make($request->all(),[
                'titulo' => 'required|string|min:10|max:2000',
                'contenido' => 'required|string|min:20|max:2000',
                'url_archivo' => 'required|file',
                'id_categoria' => 'required|integer|exists:categorias,id',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()],400);
            }

            $ruta = Storage::disk('local')->put('archivos', $request->url_archivo);  
            
            $documentacion = new Documentaciones;  //nueva documentacion
            $documentacion->titulo = $request['titulo'];
            $documentacion->contenido = $request['contenido'];
            $documentacion->url_archivo = $ruta;
            $documentacion->id_categoria = $request['id_categoria'];
            $documentacion->created_by = auth()->id();

            if ($documentacion->save()) {
                
                $newPublicacion = new Publicaciones; // nuvea publicacion
                $newPublicacion->id_user = auth()->id();
                $newPublicacion->id_documentacion = $documentacion['id'];
                $newPublicacion->created_by = auth()->id();
                $newPublicacion->save();
            
                return response()->json([
                    'message'=> 'se a creado satisfactoriamente',
                    'documentacion' => $documentacion,
                    'publicacion' => $newPublicacion
                ],201);    
            }

            return [
                'message'=> 'no se a podido crear la publicacion',
                'documentacion' => $documentacion,
            ]; 

    }



    public function listarPublicaciones(){


        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }



















}
