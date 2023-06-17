<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function perfil()
    {


        if (auth()->user()) {
            return response()->json([
                'message' => 'successfuly',
                'user_Active' => auth()->user(),
            ],200);    
        }

        return response()->json([
            'message' => 'autenticacion no encontrada'
        ]);

    }

    


    
    public function eliminarUsuario(Request $request){

        try {

            $user = User::find($request->id);
            if (empty($user)) return "no existe el usuario";

            $user->delete();
            return [
                'message' => 'Usuario eliminado',
                'delete'  => $user
            ];

        } catch (\Exception $th) {
            return $th->getMessage();
        }




    }




}
