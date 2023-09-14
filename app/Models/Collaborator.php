<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Esta classe representa as informações do colaborador do portal das disciplina,
 * como programadores, web designers, coordenadores, etc...
 */
class Collaborator extends Model
{
    use HasFactory;
    /**
     * Atributos que são atribuíveis em massa.\n
     * name: Nome do colaborador.\n
     * bond: Vinculo do colaborador com o projeto.\n
     * role: Função do colaborador no projeto.\n
     * email: E-mail do colaborador.\n
     * lattes: Link da url do perfil lattes do colaborador.\n
     * github: Link da url do perfil do github do colaborador.\n
     * urlPhoto: Local da photo do colaborador no servidor.\n
     * isManager: Determina se o colaborador é coordenador ou não.\n
     * active: Determina se o colaborador está ativo no projeto.
     */
    protected $fillable = [
        'name',
        'bond',
        'role',
        'email',
        'lattes',
        'github',
        'urlPhoto',
        'isManager',
        'active',
        'joinDate',
        'leaveDate'
    ];

    /**
     * Retorna todos os links de redes sociais do colaborador
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links(){
        return $this->hasMany(CollaboratorLink::class);
    }


    public function productions(){
        return $this->hasMany(CollaboratorProduction::class);
    }

}
