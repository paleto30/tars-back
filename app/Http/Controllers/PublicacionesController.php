<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        if(auth()->user()) {

            /* 
                en esta funcion estoy tratando de capturar el registro de una documentacion
            */

            $validateData = Validator::make($request->all(),[
                'titulo' => 'required|string|min:6|max:255|exists:documentaciones,titulo',
                'contenido' => 'required|string|min:240|max:2000|exists:documentaciones,contenido',
                'url_archivo' => 'required|string',
                'id_categoria' => 'required|integer|exists:categorias,id',
                'created_by' => 'required|integer|exists:users,id'
            ]);
            
        }


    }

}
