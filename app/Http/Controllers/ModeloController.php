<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    protected $modelo;
    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }
    public function index(Request $request)
    {//Aidiconando filtros na consultas dos modelos e manipulando a resposta json
        $modeloRepository = new ModeloRepository($this->modelo);
        if($request->has('atributos_marca')) {
            $atributos_marca = 'marca:id,'.$request->atributos_marca;
            $modeloRepository->selectAtributosRelacionados($atributos_marca);
        }else{
            $modeloRepository->selectAtributosRelacionados('marca');
        }

        if($request->has('filtro')){
            $modeloRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $modeloRepository->selectAtributos($request->atributos);
        }
        return response()->json($modeloRepository->getResultado(), 200); //HTTP 200

        //Lógica sem usar o repository abaixo.
        $atributos = $request->get('atributos') ? $request->get('atributos') : false; //Eu sei que esta definido abaixo, dentro do if.
        $atributos_marca = $request->get('atributos_marca') ? $request->get('atributos_marca') : false; //Eu sei que esta definido abaixo, dentro do if.
        //http://127.0.0.1:8000/api/modelo?atributos=id,nome,imagem,marca_id&atributos_marca=nome&filtro=nome:=:%M% //Vai trazer todos os regitros que tem 'M', nao importa o lado.
        //Podemos fazer mais e mais comparações: http://127.0.0.1:8000/api/modelo?atributos=id,nome,imagem,marca_id&atributos_marca=nome&filtro=nome:=:%M%;abs:>:2
        if($request->has('atributos') && $request->has('atributos_marca') && $request->has('filtro')){ ///Filtrando os damos mais ainda usando o valor filtr:=:Carro, para podemos fazer um explode de ':'   

            $filtros = explode(';', $request->get('filtro')); //Separando os inumeros filtros. A query é essa http://127.0.0.1:8000/api/modelo?atributos=id,nome,imagem,marca_id&atributos_marca=nome&filtro=nome:=:%M%;abs:>:2
            $condicional = []; $i = 0;
            $query = Modelo::selectRaw($atributos)
                            ->with('marca:id,'.$atributos_marca);

            foreach($filtros as $filtro){//Encadeando os filtros com foreach
                $condicional[$i] = explode(':', $filtro); //Explode
                // var_dump($condicional[$i][0], $condicional[$i][1], $condicional[$i][2]);
                $query->where($condicional[$i][0], $condicional[$i][1], $condicional[$i][2]);  //Respectivamente: atributo, operador, valor.
                $i++;
            } //Tem uma função chamada array_combine() interessante, eu deixei de usar no caso de passar mais filtros.
            return response()->json($query->get(), 200);
        }
        if($request->has('atributos') && $request->has('atributos_marca')){
            $atributos = $request->get('atributos'); //http://127.0.0.1:8000/api/modelo?atributos=id,nome,imagem
            $atributos_marca = $request->get('atributos_marca'); //Filtrando os dados entre do relacionamento na tabela relacionada. http://127.0.0.1:8000/api/modelo?atributos=id,nome,imagem,marca_id&atributos_marca=nome,imagem
            //Para isso modificamos o with('tabela_relacionada:parametros'); OBS: PRECISAMOS COLOCA O ID DA TABELA 'marca:id'
            /**
             * Utilizando com select(); da forma que o $request->get('atributos'); traz pode dar problema
             * Por isso podemos usar o selectRaw(), ele reconhece as colunas com ',' que é o retorno de atributos do request http://127.0.0.1:8000/api/modelo?atributos=id,nome,imagem
             */
            // dd($atributos_marca);
            return response()->json(Modelo::selectRaw($atributos)->with('marca:id,'.$atributos_marca)->get(),200);
            /**
             * Para recuperarmos o relacionamento entre as tabelas, precisamos passar a foreign do relacionamento estando na query string:
             * http://127.0.0.1:8000/api/modelo?atributos=id,nome,imagem,marca_id
             */
            
            // http://127.0.0.1:8000/api/modelo?atributos=id,nome,imagem,marca_id&atributos_marca=nome,imagem
        }
        if($request->has('atributos')){
            return response()->json(Modelo::selectRaw($atributos)->get(), 200);
        }//Se atributos_marca sozinho estiver preenchido vai trazer todos os dados abaixo.

        return response()->json(Modelo::with('marca')->get(), 200);
        /**
         * all() = traz uma collection.
         * get() = voce preparar uma query para trazer uma collection.
         */
    }

    public function store(Request $request)
    {
        $modelo = new Modelo();
        $validated = $request->validate($modelo->rules());
        $imagem = $request->file('imagem')->store('imagens/modelos', 'public');
        $modelo = tap(new Modelo(), function(Modelo $modelo) use($validated, $imagem){
            $modelo->marca_id = $validated['marca_id'];
            $modelo->nome = $validated['nome'];
            $modelo->imagem = $imagem;
            $modelo->numero_portas = $validated['numero_portas'];
            $modelo->lugares = $validated['lugares'];
            $modelo->air_bag = $validated['air_bag'];
            $modelo->abs = $validated['abs'];
            $modelo->save();
            /**
             * Não to gostando de utilizar o tap, vou parar de usar :C
             */
        });
        return response()->json($modelo, 201);
    }

    public function show(int $id)
    {
        $modelo = Modelo::with('marca')->find($id);
        if($modelo === null){
            return response()->json(['error' => 'O recurso nao foi encontrado'], 404);
        }
        return response()->json($modelo, 200);
    }

    public function update(Request $request, int $id)
    {
        $modelo = Modelo::find($id);
        if($modelo === null){
            return response()->json(['error' => 'O recurso nao foi encontrado'], 404);
        }

        if($request->method() === 'PATCH'){
            $rules_patch = [];
            foreach($modelo->rules() as $rule => $valor){
                if(array_key_exists($rule, $request->all())){ 
                    $rules_patch[$rule] = $valor;
                }
            }
            $validated = $request->validate($rules_patch);

            if(isset($validated['imagem']) ? $validated['imagem'] != $modelo->imagem : false){ 
                Storage::disk('public')->delete($modelo->imagem);
            }

            $modelo = tap($modelo)->update([
                'marca_id' => $validated['marca_id'] ?? $modelo->marca_id,
                'nome' => $validated['nome'] ?? $modelo->nome,
                'imagem' => isset($validated['imagem']) ? $validated['imagem']->store('imagens/modelos','public') : $modelo->imagem,
                'numero_portas' => $validated['numero_portas'] ?? $modelo->numero_portas,
                'lugares' => $validated['lugares'] ?? $modelo->lugares,
                'air_bag' => $validated['air_bag'] ?? $modelo->air_bag,
                'abs' => $validated['abs'] ?? $modelo->abs
            ]);

            return response()->json($modelo, 200);
        }

        $validated = $request->validate($modelo->rules());

        $validated['imagem'] = $validated['imagem']->store('imagens/modelos','public');

        Storage::disk('public')->delete($modelo->imagem);

        $modelo = tap($modelo)->update([
            'marca_id' => $validated['marca_id'],
            'nome' => $validated['nome'],
            'imagem' => $validated['imagem'],
            'numero_portas' => $validated['numero_portas'],
            'lugares' => $validated['lugares'],
            'air_bag' => $validated['air_bag'],
            'abs' => $validated['abs']
        ]);
        return response()->json($modelo, 200);
    }

    public function destroy(int $id)
    {
        $modelo = Modelo::find($id);
        if($modelo === null){
            return response()->json(['error' => 'O recurso nao foi encontrado!'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        
        return response()->json(['mensagem' => 'O modelo foi REMOVIDO com sucesso!'], 200);
    }
}
