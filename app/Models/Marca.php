<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];
    protected $hidden = ['created_at', 'updated_at']; //Util para esconder informações na resposta do json.
    /**
     * Abaixo é a implementação de uma validação dinamica com o __construct do Controlador de Marca, é interessante pode reutilizar o código e colocar fixo em um determiando local
     * Olhe o __construct de MarcaController para mais informações
     */
    public static function rules(){
        return [
            'nome' => 'required|unique:marcas|min:3',
            'imagem' => 'required'
        ];
    }
    public static function messages(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'unique' => 'O :attribute ja existe no banco de dados',
            'min' => 'O :attribute deve ter no minimo 3 caracteres'
        ];
    }
}
