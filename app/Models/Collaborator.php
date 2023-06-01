<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Esta classe representa as informações do colaborador do portal das disciplina.
 *
 */
class Collaborator extends Model
{
    use HasFactory;
    /**
     * @param name Nome do colaborador
     * @param bond Vinculo do colaborador com o projeto
     * @param role Função do colaborador no projeto
     * @param email E-mail do colaborador
     * @param lattes Endereço do link do colaborador do seu perfil lattes.
     * @param github Endereço do link do github do colaborador caso possua.
     * @param urlPhoto Nome do arquivo da foto do colaborador.
     * @param isManager Caso o colaborador seja coordenador, o valor é True. False caso contrário.
     * @param active Caso o colaborador esteja ativo no projeto, o valor é True. False caso contrário
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
        'active'
    ];

    /**
     * Retorna todos os links de redes sociais do colaborador
     * @return <array,Collaborator>
     */
    public function links(){
        return $this->hasMany(CollaboratorLink::class);
    }
}
