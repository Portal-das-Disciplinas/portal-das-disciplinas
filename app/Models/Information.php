<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Guarda alguma informação que pode ser editada no site, como o nome
 * da seção coordenador, usuários ativos e inativos da view sobre
 * 
 */
class Information extends Model
{
    use HasFactory;

    protected $table = "informations";/*!<Nome da tabela que representa esse modelo */

    /**
     * Nomes dos campos da classe
     * @param name é uma chave única que irá referenciar o nome no banco
     * @param value O valor da informção.
     */
    protected $fillable = [
        'name',
        'value',
    ];
}
