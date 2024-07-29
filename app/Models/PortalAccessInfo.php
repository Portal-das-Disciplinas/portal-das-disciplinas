<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalAccessInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "ip",
        "path",
        "accessed_on"
    ];
}


