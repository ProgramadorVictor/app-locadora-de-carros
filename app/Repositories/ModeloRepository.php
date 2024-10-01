<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model; //Para utilização do type hinting
class ModeloRepository{
    /**
     * Design Pattern são padrões de projetos que fazemos para melhora varios aspectos como Legibilidade do Código, Manutenção de Código, entre outras
     */
    protected $model;
    public function __construct(Model $model){ //Deve receber um model
        $this->model = $model;
    }
    public function selectAtributosRelacionados($atributos){
        $this->model = $this->model->with($atributos); //Guardando o estado da query que esta sendo montada, assim atualizando e atualizando e depois ->get(); para obter os dados com varias combinações
    }
    public function filtro($parametros){ //Recebendo $request->filtro
        $filtros = explode(';', $parametros);
        foreach($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]); //Montando a $query
        }
    }
    public function selectAtributos($atributos){
        $this->model = $this->model->selectRaw($atributos);
    }
    public function getResultado(){
        return $this->model->get();
    }
}