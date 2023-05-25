<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaboratorLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'collaborator_id'
    ];

    public function collaborator(){
        return $this->belongsTo(Collaborator::class);
    }
}
