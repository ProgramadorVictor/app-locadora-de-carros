<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function index()
    {
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
