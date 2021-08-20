<?php

namespace App\Models;

use App\Enums\RoleName;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
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
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role->priority_level == 999;
    }
    /**
     * @return bool
     */
    public function getIsProfessorAttribute(): bool
    {
        return $this->role->priority_level == 2;
    }
        /**
     * @return bool
     */
    public function getIsStudentAttribute(): bool
    {
        return $this->role->priority_level == 1;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function professor()
    {
        return $this->hasOne(Professor::class);
    }
}
