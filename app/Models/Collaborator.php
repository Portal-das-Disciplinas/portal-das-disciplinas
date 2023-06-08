<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory;

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



    public function links(){
        return $this->hasMany(CollaboratorLink::class);
    }

}
