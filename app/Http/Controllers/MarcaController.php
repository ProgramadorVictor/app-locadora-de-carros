<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        /**
         * As requisições estão sendo realizadas via Postman é um aplicativo feito para ver o funcionamento da API Rest
         */
        $marca = tap(new Marca(), function(Marca $marca) use ($request) {
            $marca->nome = $request->input('nome');
            $marca->imagem = $request->input('imagem');
            $marca->save();
        });
        return $marca; //Laravel entende que o objeto retornado deve ser 'application/json' mesmo a gente não convertendo ele manualmente.
    }

    public function show(Marca $marca)
    {
        //
    }

    public function edit(Marca $marca)
    {
        //
    }

    public function update(Request $request, Marca $marca)
    {
        //
    }

    public function destroy(Marca $marca)
    {
        //
    }
}
