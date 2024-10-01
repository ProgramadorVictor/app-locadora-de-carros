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
        auth('api')->logout(); //Colocando um token na blacklist assim desativando o token.
        return response()->json('Logout realizado com sucesso', Response::HTTP_OK);
    }
    public function refresh(){//Esta dentro da middleware jwt. Ou seja é necessario ter um token valido para renova o token.
        $token = auth('api')->refresh();//Renova o token, auth esta indicando qual driver esta sendo utilizado. 
        return response()->json(['token' => $token], Response::HTTP_OK);
    }
    public function me(){ //O usuario autenticado ao receber o token esse token irá ficar vinculado ao usuario, podemos recuperar os dados deste usuario.
        return response()->json(auth()->user(), Response::HTTP_OK); //Recuperando o usuario que esta relacionado com o token.
        //Uma coisa que percebi sobre este token é possivel vulnerabiliade de CSS, pois o token aparentemente não é guardado no banco, parece que é armazenado no lado do cliente. Sendo possivel ataque de XSS.
    }
}