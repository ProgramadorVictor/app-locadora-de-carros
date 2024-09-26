<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MarcaController extends Controller
{
    public function index(): Collection
    {
        /**
         * Retornandos todos os registros de marca e retornando uma collection.
         * Fazendo requisição com verbo GET, pois é o verbo da index.
         */
        return Marca::all();
    }

    public function store(Request $request): Marca
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

    public function show(Marca $marca): Marca
    {
        /**
         * Fazendo solicitação get com parametro 'id' para obter o dado do objeto Marca correspondente.
         */
        return $marca;
    }

    public function update(Request $request, Marca $marca): Marca
    {
        /**
         * Atualizando os dados com put e patch.
         * Put sendo utilizado para atualizar mais de 1 dado.
         * Patch sendo utilizado apenas para atualizar 1 dado. (soft)
         */
        $marca = tap($marca)->update([
            'nome' => $request->input('nome'),
            'imagem' => $request->input('imagem')
        ]);
        return $marca;
    }

    public function destroy(Marca $marca)
    {
        $marca->delete();
        return [
            'mensagem' => 'A marca foi removida com sucesso!'
        ];
    }
    /**
     * Desta forma implementamos um web service, API OBSERVE REST fazendo operações CRUD no Model Marca.
     * Respeitando os padrões estruturados do API REST com os Verbos HTTPS.
     */
}
