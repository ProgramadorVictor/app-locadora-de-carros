<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

class MarcaController extends Controller
{
    public function index(): Collection
    {
        /**
         * Retornandos todos os registros de marca e retornando uma collection.
         * Fazendo requisição com verbo GET, pois é o verbo da index.
         */
        return Marca::all(); //HTTP 200
    }

    public function store(Request $request): JsonResponse
    {
        /**
         * As requisições estão sendo realizadas via Postman é um aplicativo feito para ver o funcionamento da API Rest
         */
        $marca = tap(new Marca(), function(Marca $marca) use ($request) {
            $marca->nome = $request->input('nome');
            $marca->imagem = $request->input('imagem');
            $marca->save();
        });
        return response()->json($marca,201); //Laravel entende que o objeto retornado deve ser 'application/json' mesmo a gente não convertendo ele manualmente.
    }

    // public function show(Marca $marca): Marca //Com essa tipagem retornar um Throw Exception
    public function show($id) //Tirando o type hinting de show()
    {
        /**
         * Fazendo solicitação get com parametro 'id' para obter o dado do objeto Marca correspondente.
         */
        $marca = Marca::find($id);
        if($marca === null){ // '===' Identico precisa ser do mesmo tipo e do mesmo valor
            /**
             * Retornando uma resposta com response, indicando que a resposta é um objeto json.
             * Códigos HTTP: oferecem mais semantica para a resposta HTTP, consulte o site: https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Status
             */
            return response()->json(['error' => 'O recurso nao foi encontrado'], 404); //Passando o status para ter semântica com o retorno da resposta no lado do cliente. Anteriormente estava retornando 200.
        }
        return response()->json($marca, 200);
    }

    // public function update(Request $request, Marca $marca): Marca
    public function update(Request $request, $id) //Tirando o type hinting de update()
    {
        /**
         * Atualizando os dados com put e patch.
         * Put sendo utilizado para atualizar mais de 1 dado.
         * Patch sendo utilizado apenas para atualizar 1 dado. (soft)
         */
        $marca = Marca::find($id);
        if($marca === null){
            return response()->json(['error' => 'O recurso nao foi encontrado'], 404); //Retornando o array que vai ser convertido em json posteriormente pelo Laravel
        }
        $marca = tap($marca)->update([
            'nome' => $request->input('nome'),
            'imagem' => $request->input('imagem')
        ]);
        return response()->json($marca, 200);
    }

    public function destroy($id)
    {
        $marca = Marca::find($id);
        if($marca === null){
            return response()->json(['error' => 'O recurso nao foi encontrado!'], 404);
        }
        $marca->delete();
        return response()->json(['mensagem' => 'A marca foi removida com sucesso!'], 200);
    }
    /**
     * Desta forma implementamos um web service, API OBSERVE REST fazendo operações CRUD no Model Marca.
     * Respeitando os padrões estruturados do API REST com os Verbos HTTPS.
     */
}
