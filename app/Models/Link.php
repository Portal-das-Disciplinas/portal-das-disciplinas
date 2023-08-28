<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Esta classe tem por função guardar o endereço de um link que é editável em qualquer parte do portal.\n
 * name: Nome único do link.\n
 * url: Endereço do link.\n
 * ativo: Informa se o link está ativo ou inativo.
 */
class Link extends Model
{
    protected $fillable = [
        'name',
        'url',
        'ativo'
    ];
    use HasFactory;
}