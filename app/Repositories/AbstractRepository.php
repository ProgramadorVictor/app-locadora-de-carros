<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model; //Para utilização do type hinting
class AbstractRepository{
    /**
     * O que for comum podemos colocar aqui, o que for especifico de cada classe colocamos nos outros Repository.
     */
    protected $model;
    public function __construct(Model $model){
        $this->model = $model;
    }
    public function selectAtributosRelacionados($atributos){
        $this->model = $this->model->with($atributos); 
    }
    public function filtro($parametros){
        $filtros = explode(';', $parametros);
        foreach($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]); 
        }
    }
    public function selectAtributos($atributos){
        $this->model = $this->model->selectRaw($atributos);
    }
    public function getResultado(){
        return $this->model->get();
    }
}