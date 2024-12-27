<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionalUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'acronym'
    ];

    public function professors(){
        return $this->hasMany(Professor::class);
    }

    public function curses(){

        return $this->hasMany(Course::class);
    }

    public function unitAdmin(){
        return $this->hasOne(UnitAdmin::class);
    }
}
