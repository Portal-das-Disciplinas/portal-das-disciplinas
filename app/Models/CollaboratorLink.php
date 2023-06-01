<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Objeto que guarda o link do colaborador do projeto Portal das Disciplinas.
 */
class CollaboratorLink extends Model
{
    use HasFactory;
    /**
     * Nome dos campos do modelo no banco de dados.
     * @param name Nome do link da rede social do colaborador.
     * @param url Endereço do link da rede social do colaborador.
     * @param collaborator_id ID do colaborador do qual o link pertence.
     */
    protected $fillable = [
        'name',
        'url',
        'collaborator_id'
    ];

    /**
     * Retorna o objeto Collaborator do qual o link pertence
     * @return Collaborator
     */
    public function collaborator(){
        return $this->belongsTo(Collaborator::class);
    }
}
