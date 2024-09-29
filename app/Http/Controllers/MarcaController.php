<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    /**
     * Abaixo, estamos fazendo uma validação dinamica no Laravel, diretamente no próprio Model, muito interessante.
     * Pois ao utilizar desta prática os dados fornecem uma reutilização de código pratica e podemos simplesmente alterar no Model.
     *  '$validated = $request->validate($this->marca->rules(), $this->marca->messages());'
     */
    // public function __construct(Marca $marca){ //Preferir fazer de uma forma diferente utilizando métodos estaticos.
    //     $this->marca = $marca;
    // }
    /**
     * Essas especificações abaixo, como os parametros a ser recebido Request $request, Marca $marca
     * Os tipos de retorno esperado como Json, Collection, JsonResponse, Marca. São Type Hints
     * Eles tipam o dado que vai ser recebido e/ou o que vai ser retornado, caso os dados sejam diferente possivelmente pode ocorrer erros
     * @return \Illuminate\Support\Collection
     * Esse documento ou tipo de comentário se chama 'DocBlock' bem útil, em várias contem ele.
     */
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
        // $validated = $request->validate($this->marca->rules(), $this->marca->messages()); //A propria validação do Laravel ja retornar o status code correto e enviar um json com as mensagens. OBS: Deve ter no cabeçalho da requisição o 'Accept' que indica que o client aceita receber dados do tipo 'application/json'.
        $marca = new Marca();
        $validated = $request->validate($marca->rules(), Marca::messages()); //Esta forma é interessante de fazer, também podemos usar o FormRequest
        
        // var_dump($request->file('imagem')); var_dump($request->get('nome')); //file() para arquivos, get() para outros.
        /**
         * Salvamento de imagens
         * Local: São arquivos protegidos pelo '/storage/app/', usado para guardar arquivos que não devem ficar disponivel para todos.
         * Public: São arquivos protegidos pelo '/storage/app/public', para que os arquivos fiquem disponivel para todos, precisamos configurá um link simbolico
         * AWS: Podemos configurar o driver de AWS (Amazon WEB Services), mais especificamente 'S3' Serviçod e armazenamento simples da Amazon, para que os arquivos sejam persitidos e consumidos diretamente da Amazon. Interessante porque podemos reduzir a banda do nosso servidor.
         */
        //Um detalhe importante podemos enviar um array de imagens no lado do client.
        $imagem = $request->file('imagem')->store('imagens', 'public');
        if(false){
            $imagem->store('imagens'); //Adiciona a imagem no diretório imagens, pode receber dois parametros. Deve ser configurado em config/filesystems.php
            $imagem->store('imagens', 'public'); //Salva no diretorio imagens dentro da pasta public.
            
            $request->file('imagem')->store('imagens', 'public');
            $request->file('imagem')->storeAs('imagens', 'nome_do_arquivo.extensao', 'public'); //Salva o arquivo com um nome extensão modificado, no disco 'public' se não tiver o terceiro parametro salva em 'default'
            
            $arquivos = Storage::allFiles('imagens'); //Traz todos os arquivos até os sub-diretorios
            $arquivos = Storage::files('imagens'); //Traz todos os arquivos menos os sub-diretorios
            
            Storage::put('imagens', $imagem); //Mesma coisa, do store(), salva diretamente no 'default' de 'config/fiylesystems.php'
            Storage::disk('public')->put('imagens', $imagem); //Esse aqui direciona para o disco 'public' e salva a imagem lá
        }
        //Quando uma aplicação é stateless (sem estado), isso significa que cada requisição é independente, e o servidor não mantém informações sobre o estado anterior do usuário. Pode ocorrer um problema quando a requisição API estiver com os dados errados redirecionado o usuario para uma pagina errada.
        //Para resolvemos o problema precisamos indicar no cabeçalho do lado do cliente o atributo 'Accept' que indica que o lado do cliente sabe resolver esse problema de redirecionamento do retorno json.
        $marca = tap(new Marca(), function(Marca $marca) use ($validated, $imagem) {
            $marca->nome = $validated['nome'];
            $marca->imagem = $imagem;
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
        //OBS: Ocorreu um bug no proprio Laravel, pelos foruns esse bug existe até hoje, a solução deles é enviar como post e validar se é patch ou put
        //Esse bug é que no envio do contexto de 'form-data' os dados não chegam via 'put' ou 'patch'. Literalmente isso abaixo parou de funcionar do nada.
        //Ou mandamos uam requisição 'POST' com no body '_method' = 'PATCH' ou 'PUT'.

        /**
         * Atualizando os dados com put e patch.
         * Put sendo utilizado para atualizar mais de 1 dado.
         * Patch sendo utilizado apenas para atualizar 1 dado. (soft)
         */
        $marca = Marca::find($id);
        if($marca === null){
            return response()->json(['error' => 'O recurso nao foi encontrado'], 404); //Retornando o array que vai ser convertido em json posteriormente pelo Laravel
        }

        if($request->method() === 'PATCH'){ //method() recupera o metodo do request isMethod('PATCH') verifica se o método é patch
            $rules_patch = [];
            foreach($marca->rules() as $rule => $valor){
                if(array_key_exists($rule, $request->all())){ //$request->input() não reconhece arquivos diferente de campo de texto, como 'file'
                    $rules_patch[$rule] = $valor;
                }
            }
            $validated = $request->validate($rules_patch, Marca::messages());

            $nome = $validated['nome'] ?? $marca->nome;
            $imagem = isset($validated['imagem']) ? $validated['imagem']->store('imagens','public') : $marca->imagem;

            if($imagem != $marca->imagem){ //Apagando a imagem anterior verificado se houver uma alteração.
                Storage::disk('public')->delete($marca->imagem);
            }

            $marca = tap($marca)->update([
                'nome' => $nome,
                'imagem' => $imagem
            ]);

            return response()->json($marca, 200);
        }
        $validated = $request->validate($marca->rules(), Marca::messages());
        $validated['imagem'] = $validated['imagem']->store('imagens','public');

        Storage::disk('public')->delete($marca->imagem);

        $marca = tap($marca)->update([
            'nome' => $validated['nome'],
            'imagem' => $validated['imagem']
        ]);
        return response()->json($marca, 200);
    }

    public function destroy($id)
    {
        $marca = Marca::find($id);
        if($marca === null){
            return response()->json(['error' => 'O recurso nao foi encontrado!'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['mensagem' => 'A marca foi removida com sucesso!'], 200);
    }
    /**
     * Desta forma implementamos um web service, API OBSERVE REST fazendo operações CRUD no Model Marca.
     * Respeitando os padrões estruturados do API REST com os Verbos HTTPS.
     */
}
