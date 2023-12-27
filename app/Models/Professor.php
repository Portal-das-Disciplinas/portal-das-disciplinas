<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe que guarda as informações de um professor.
 */
class Professor extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada com o modelo.
     */
    protected $table = 'professors';

    /**
     * Array com os atributos que são atribuíveis em massa pelo método create.\n
     * name: Nome do professor.\n
     * profile_pic_link: Url do link da foto do professor.\n
     * public_email: E-mail público do professor.(Não usado no login)
     * rede_social1: Nome da primeira rede social do professor.\n
     * link_rsocial1: Url do link da primeira rede social do professor.\n
     * rede_social2: Nome da segunda rede social do professor.\n
     * link_rsocial2: Url do link da segunda rede social do professor.\n
     * rede_social3: Nome da terceira rede social do professor.\n
     * link_rsocial3: Url do link da terceira rede social do professor.\n
     * rede_social4: Nome da quarta rede social do professor.\n
     * link_rsocial4: Url do link da quarta rede social do professor.\n
     * user_id: ID do usuário do qual o professor está vinculado.
     */
    protected $fillable = [
        'name',
        'profile_pic_link',
        'public_email',
        'rede_social1',
        'link_rsocial1',
        'rede_social2',
        'link_rsocial2',
        'rede_social3',
        'link_rsocial3',
        'rede_social4',
        'link_rsocial4',
        'user_id',
    ];

    /**
     * Retorna o usuário(User) que está vinculado ao professor.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
