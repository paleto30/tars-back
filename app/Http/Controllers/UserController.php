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

    


    





}
