<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssocProject extends Model
{
    use HasFactory;

    protected $table = 'associated_projects';

    protected $fillable = [
        'discipline_id',
        'name',
        'desc',
        'link'
    ];
}
