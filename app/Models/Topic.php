<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';
    protected $fillable = ['title'];

    public function disciplines() {
        return $this->belongsToMany('App\Models\Discipline');
    }
}
