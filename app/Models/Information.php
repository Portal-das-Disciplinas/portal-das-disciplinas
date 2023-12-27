<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Guarda alguma informação que pode ser editada no site, como o nome
 * da seção coordenador, usuários ativos e inativos da view sobre.
 */
class Information extends Model
{
    use HasFactory;

    protected $table = "informations";/*!<Nome da tabela que representa esse modelo */

    /**
     * Array com os atributos que são associados em massa.\n
     * name: Nome único para identificação da informação.\n
     * value: Valor da informação.
     */
    protected $fillable = [
        'name',
        'value',
    ];
}
