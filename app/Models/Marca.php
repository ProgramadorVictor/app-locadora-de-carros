<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nome', 'imagem'];
    protected $hidden = ['created_at', 'updated_at']; //Util para esconder informações na resposta do json.
    /**
     * Abaixo é a implementação de uma validação dinamica com o __construct do Controlador de Marca, é interessante pode reutilizar o código e colocar fixo em um determiando local
     * Olhe o __construct de MarcaController para mais informações
     */
    
    public function rules(){
        return [
            /**
             * unique: tabela, coluna, id
             * O primeiro parametro é a tabela é obrigatório
             * O segundo parametro é opcional caso não deseje um terceiro, por padrão é o nome do array do request, exemplo: nome, imagem
             * O terceiro parametro é o id que deve se desconsiderado na  hora da busca, caso queira o mesmo nome para determinado dado do banco de dados. Ex: abaixo
             * Ex: Quero mudar um dado unico, porem o dado ja existe no banco de dados. Se não houver 3 parametros vai ocorrer uma Exception dizendo que o dado ja existe.
             */
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3', //Isso funciona somente quando o dado tem uma instancia. Por exemplo com __construct recuperando e passado a isntancia para Model Marca
            // 'nome' => 'required|unique:marcas,nome,id|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg' //Tratativas para imagem, pode ser usado o 'image' que é uma trativa do Laravel que ja tem imagens
            //Acima o mimes: indica quais '.extensao' podem ser, podemos colocar outras também.
        ];
    }
    // Pode ser feito da maneira abaixo passando uma instancia para rules()
    // public static function rules(Marca $marca){} //new Marca() | $marca
    public static function messages(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'unique' => 'O :attribute ja existe no banco de dados',
            'min' => 'O :attribute deve ter no minimo 3 caracteres',
            'mimes' => 'A :attribute deve ser uma imagem do tipo png,jpeg,jpg.'
        ];
    }
    public function modelos(){
        return $this->hasMany(Modelo::class);
    }
}
