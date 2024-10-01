<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\AuthController;

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
Route::prefix('v1')->middleware('jwt')->group(function(){ //Fazendo o versionamento da api, implementando o middleware.
    //O token deve ser passado no cabeçalho 'Authorization' o valor tem que ser 'Bearer $token'
    Route::apiResource('/cliente', ClienteController::class);
    Route::apiResource('/carro', CarroController::class);
    Route::apiResource('/locacao', LocacaoController::class);
    Route::apiResource('/marca', MarcaController::class);
    Route::apiResource('/modelo', ModeloController::class);
});
/**
 * Um detalhe importante!: Ao solicitar a requisição API, deve ter os códigos HTTP que corresponde ao status HTTP, é mais semantico
 * Requisições realizadas que retornam algum tipo de problema ao esta faltando dado, lado do cliente deve ter no cabeçalho da Requisição API, o atributo 'Accept' com o value da solicitação do tipo de arquivo. Ex: application/json
 * Pois se não haver, o lado do servidor acaba direcionando para a pagina errada, no Laravel estava direcionando para a pagina padrão '/'. Ao usar 'Accept' no cabeçalho, isso indica que o lado do cliente sabe como lidar com o problema da requisição.
 */
/**
 * API: Interface de Programação de Aplicação ou Application Programming Interface.
 * API é a comunicação entre aplicações de modo que ocorre solicitações recebendo chamadas API.
 * Podem ocorre trocas de funcionalidaes ou informações.
 * Stateless (Sem estado): Aplicações que não mantem informações sobre o usuario, assim cada requisição sendo de forma independente. Ex: Clima atual, Conversão de Dinheiro.
 * Para mais segurança ao usar 'Stateless', utilize um JWT - JSON Web Tokens para proteger dados com o uso de tokens, a cada requisição.
 * Stateful (Com estado): Aplicações que mantem informações sobre o usuario (necessário autenticação), assim cada requisição sendo guardada, oferecer UX ao usuario. Ex: Favoritos, Dados do suario, Lista de tarefas.
 * Para mais segurança ao usar 'Stateful', garanta a segurança contra CSRF (Cross Site Request Forgery).
 * API REST (Representational State Transfer): Somente protocolos HTTP, suporta varios formatos como: JSON, XML, HTML... Comumente é usado com aplicações 'stateless', suporte a cache e mais simples de usar, depende de HTTPS para segurança.
 * API SOAP (Simple Object Access Protocol): Utiliza varios protocolos como HTTP, SMTP, TCP. Suporta apenas um formato XML, Comumente é usado com aplicações 'stateful', mais complexo e utiliza WS-Security para segurança. Utiliza WSDL usado para descrever a solicitação, quais serviços vao ser acessados
 * AVISO: API REST E SOAP, ambas podem ser usadas para ao contrário do seu uso comum, estado ou sem estado. Porém não é recomendável.
 * AVISO: APIs podem ter versionamento sendo usado para identificar qual versão de API estamos usando podemos especificar na urn /api/v1/data
 * Endpoint API: É o toda URI que solicitamos uma requisição API 'http://127.0.0.1:8000/api/marca/1'. Isso indica a URI = URL + URN
 */
/**
 * Para usar uma API Web Service REST, no qual é uma API 'stateless' para uma forma de segurança de acesso aos dados podemos implementar o JWT Json Web Token.
 * Autenticamos um usuario na primeira requisição, ao realizar a autenticação, geramos um token de autorização e retornamos o token para o 'Bearer' de modo que ele utilize este token para requisições futuras.
 * Autenticação é diferente de Autorização
 * Podemos deixar mais seguro, limitando o tempo do token ou um limite de requisições com este token. De modo que o 'Bearer' irá ter que fazer a autenticação para obter outro token.
 * Os clientes que tem o token são chamados de 'Bearer'
 * O JWT é formado por 3 partes: Header, Payload, Signature.
 * composer require tymon/jwt-auth -- Instalando o JWT Token
 */
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('me', [AuthController::class, 'me']);