<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Representa informações sobre uma produção do colaborador no portal.
 */
class CollaboratorProduction extends Model
{
    use HasFactory;

    /**
     * Array com o atributos que podem ser atribuíveis em massa pelo método CollaboratorProduction::create.\n
     * collaborator_id: Identificados único do colaborator.\n
     * brief: Breve informação da produção do colaborador no Portal.
     * details: Informação detalhada da produção do colaborador no Portal.
     */
    protected $fillable = [
        "collaborator_id",
        "brief",
        "details"
    ];

    /**
     * Retorna o colaborador desta produção do portal
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function collaborator(){
        return $this->belongsTo(Collaborator::class);
    }



}
