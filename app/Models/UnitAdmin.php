<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'institutional_unit_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function institutionalUnit()
    {
        return $this->belongsTo(InstitutionalUnit::class);
    }
}
