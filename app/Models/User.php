<?php

namespace App\Models;

use App\Enums\RoleName;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Classe que contém as informações de usuário do sistema
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Array com os atributos que são atribuíveis em massa pelo método User::create.\n
     * name -> Nome do usuário.\n
     * email ->E-mail utilizando para o usuário fazer login.\n
     * password -> Senha do usuário.\n
     * role_id -> ID da Role para determinar o nível de acesso do usuário.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * Atributos que não devem ser exibidos ao obter o modelo.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que são convertidos para outros tipos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     *Retorna true se o usuário pode editar a disciplina.
     * @param $discipline
     * @return bool
     */
    public function canDiscipline($discipline): bool
    {
        if ($this->is_admin) {
            return true;
        }

        if (is_null($this->professor)) {
            return false;
        }

        if (is_numeric($discipline)) {
            $discipline = Discipline::findOrFail($discipline);
        }

        return $this->professor->id == $discipline->professor_id;
    }

    /**
     * Retorna se o usuário é um administrador.
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role->priority_level == 999;
    }

    public function getIsUnitAdminAttribute():bool
    {
        return $this->role->name == RoleName::UNIT_ADMIN;
    }
    /**
     * Retorna se o usuário é um professor.
     * @return bool
     */
    public function getIsProfessorAttribute(): bool
    {
        return $this->role->priority_level == 2;
    }
    /**
     * Retorna true se o usuário é um aluno.
     * @return bool
     */
    public function getIsStudentAttribute(): bool
    {
        return $this->role->priority_level == 1;
    }
    /**
     * Retorna o nível de acesso do usuário.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Retorna o objeto Student caso o usuário possua.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Retorna o objeto Professor caso o usuário possua.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function professor()
    {
        return $this->hasOne(Professor::class);
    }

    public function unitAdmin()
    {
        return $this->hasOne(UnitAdmin::class);
    }

}
