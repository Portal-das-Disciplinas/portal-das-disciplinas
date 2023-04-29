<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emphasis extends Model
{
    use HasFactory;

    protected $table = 'emphasis';
    
    protected $fillable = [
        'name'
    ];

    public function disciplines()
    {
        return $this->hasMany(Discipline::class);
    }
}
