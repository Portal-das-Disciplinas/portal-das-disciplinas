<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Objeto que guarda um link de uma rede social do colaborador do projeto Portal das Disciplinas.
 * 
 */
class CollaboratorLink extends Model
{
    use HasFactory;
    /**
     * Array com os atributos que são atribuíveis em massa pelo método CollaboratorLink::create.\n
     * name: Nome para referenciar o link do colaborador.\n
     * url: Url do link do colaborador.\n
     * collaborator_id: ID do colaborador que possui o link.
     */
    protected $fillable = [
        'name',
        'url',
        'collaborator_id'
    ];

    /**
     * Retorna o colaborador do qual o link pertence.
     * @return Collaborator
     */
    public function collaborator(){
        return $this->belongsTo(Collaborator::class);
    }
}
