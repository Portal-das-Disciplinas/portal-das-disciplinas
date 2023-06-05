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
     * Atributos que são atribuíveis em massa.
     * 
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
