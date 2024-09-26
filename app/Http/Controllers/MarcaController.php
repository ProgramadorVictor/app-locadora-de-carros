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

    // public function show(Marca $marca): Marca //Com essa tipagem retornar um Throw Exception
    public function show($id) //Tirando o type hinting de show()
    {
        /**
         * Fazendo solicitação get com parametro 'id' para obter o dado do objeto Marca correspondente.
         */
        $marca = Marca::find($id);
        if($marca === null){ // '===' Identico precisa ser do mesmo tipo e do mesmo valor
            return ['error' => 'O recurso nao foi encontrado']; //Retornando o array que vai ser convertido em json posteriormente pelo Laravel
        }
        return $marca;
    }

    // public function update(Request $request, Marca $marca): Marca
    public function update(Request $request, $id) //Tirando o type hinting de update()
    {
        /**
         * Atualizando os dados com put e patch.
         * Put sendo utilizado para atualizar mais de 1 dado.
         * Patch sendo utilizado apenas para atualizar 1 dado. (soft)
         */
        if(Marca::find($id) === null){
            $marca = Marca::find($id);
            $marca = tap($marca)->update([
                'nome' => $request->input('nome'),
                'imagem' => $request->input('imagem')
            ]);
            return $marca;
        }
        return ['error' => 'O recurso nao foi encontrado']; //Retornando o array que vai ser convertido em json posteriormente pelo Laravel
    }

    public function destroy($id)
    {
        $marca = Marca::find($id);
        if($marca === null){
            return [
                'error' => 'O recurso nao foi encontrado!'
            ];
        }
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
