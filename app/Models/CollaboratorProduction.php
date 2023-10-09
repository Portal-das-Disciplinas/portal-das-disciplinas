<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollaboratorProduction extends Model
{
    use HasFactory;

    protected $fillable = [
        "collaborator_id",
        "brief",
        "details"
    ];

    function collaborator(){
        return $this->belongsTo(Collaborator::class);
    }



}
