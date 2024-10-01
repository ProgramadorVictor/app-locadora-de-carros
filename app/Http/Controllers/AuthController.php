<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function login(Request $request){
        //Autenticação por usuario e senha, pós validação retornamos um token, que pode ser configurado para expirar.
        $credenciais = $request->only(['email', 'password']);
        // auth('api') //Indicando para o framework laravel que queremos trabalhar com o api, que esta em config/auth.php
        if($token = auth('api')->attempt($credenciais)){
            return response()->json(
                ['token' => $token],
                Response::HTTP_OK
            );
        }
        /**
         * Essa sintaxe de Response é interenssate utilizar. Gostei
         * 401 -- Não autorizado 
         * 403 -- Produto não encontrado ou inválido.
         */ 
        return response()->json(
            'Autenticacao falhou',
            Response::HTTP_FORBIDDEN
        );
    }
    public function logout(){

    }
    public function refresh(){

    }
    public function me(){

    }
}