<?php

namespace App\Http\Controllers;

use App\Models\Documentaciones;
use App\Models\Publicaciones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            
        try {
            
            $validator = Validator::make($request->all(),[
                'titulo' => 'required|string|min:10|max:2000',
                'contenido' => 'required|string|min:20|max:2000',
                'url_archivo' => 'required|file',
                'id_categoria' => 'required|integer|exists:categorias,id',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()],400);
            }


            $existe = Documentaciones::where('titulo',$request->titulo)->value('id');

            if (! is_null($existe)) {
                return response()->json(['message'=>'Esta documentacion ya fue registrada anteriormente']);
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
        } catch (\Throwable $th) {
            return  $th->getMessage();
        }

    }



    /* 
        en esta funcion se va a crear un endpoint que me permita listar
        todas las publicaciones que pertenezcan a un usuario especifico,
        es decir que pertenezcan al usuario activo en su sesion

        se espera que estas publicaciones vengan paginadas de a 10

        de estas publicaciones se quiere ver fecha y hora de publicacion,
        la categoria
        el titulo de la documentacion,
        la descripcion del contenido ,
        y por supuesto la ruta para consultar el archivo de dicha documentacion
    */
    public function listarPublicacionesPerfilUser(){

        try { 

            $publicaciones = User::join('publicaciones', 'users.id','=','publicaciones.id_user')
            ->join('documentaciones','publicaciones.id_documentacion','=','documentaciones.id')
            ->join('categorias','documentaciones.id_categoria','=','categorias.id')
            ->select('publicaciones.id','publicaciones.created_at','categorias.nombre','documentaciones.titulo','documentaciones.contenido','documentaciones.url_archivo')
            ->where('users.id', '=' ,auth()->id())
            ->latest()->paginate(10);
            
            return response()->json([
                'date' => $publicaciones
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                    'error'=> $th->getMessage()
            ]);
        }
    }




    

    /* 
        con el eso de la DB::transaction()
        yo puedo solicitarle que le haga
        el intento de enviar datos a la base de datos 
        y si falla el proceso , entonces que automaticamente 
        realize un rollback de los registros que agrego 
        para evitar error en el registro de datos
        
        
        tambien cuenta con una funcion ->  DB::afterCommit(callback),
        recibe un callback la cual le permite ejecutar acciones despues de realizar
        el commit de almacenamiento a la base de datos 

        tambien puedo ejecutar la transaccion 
        DB::beginTransaction();
        y en de esta manera yo controlo en que momento se hace el commit y el rollback


        FUNCIONES DE PRUEBA Y APRENDIZAJE
    */

    public function transaccion(Request $request)
    {       


        try {
            DB::transaction(function() use($request){

                $documentacion = new Documentaciones;  //nueva documentacion
                $documentacion->titulo = $request['titulo'];
                $documentacion->contenido = $request['contenido'];
                $documentacion->url_archivo = $request['url_archivo'];
                $documentacion->id_categoria = $request['id_categoria'];
                $documentacion->created_by = 1;
                
                DB::afterCommit(function() use($documentacion) {
                        echo "alguna mierda";
                });
                if ($documentacion->save()) {
                    
                    $newPublicacion = new Publicaciones; // nuvea publicacion
                    $newPublicacion->id_user = 1;
                    $newPublicacion->id_documentacion = $documentacion['id'];
                    $newPublicacion->created_by = 1;
                    $newPublicacion->save();
                }
            });
        } catch (\Throwable $th) {
            return ['error'=> 'fallo el guardado'];
        }


        return [
            'message'=> 'succesfuly'
        ];
      

       
    }




    







}
