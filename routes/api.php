<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/', function () {
    /**
     * Laravel tem a inteligencia de percebe que ao retornamos um array ele retornar uma application/json.
     * Automaticamente modifica o 'Content-Type' do cabeçalho HTML, para 'application/json'.
     */
    return ['status' => 'Chegamos até aqui, automaticamente com a inteligência do Laravel'];
});
/**
 * A uma diferença entre os métodos estaticos resource() apiResouce(). Veja abaixo;
 * Quando estamos trabalhando com API REST, não temos a necessidade de trabalhar com métodos HTTP: create, edit.
 * Não usamos create e edit. Pois não trabalhamos com trafego de conteudo HTML (conteudo html == forms com submit), apenas objetos JSON entre cliente e web service.
 * resource métodos: index, store, create, show, update, destroy, edit.
 * apiResource métodos: index, store, show, update, destroy.
 */
// Route::resource('/cliente', ClienteController::class);
Route::apiResource('/cliente', ClienteController::class);
Route::apiResource('/carro', CarroController::class);
Route::apiResource('/locacao', LocacaoController::class);
Route::apiResource('/marca', MarcaController::class);
Route::apiResource('/modelo', ModeloController::class);