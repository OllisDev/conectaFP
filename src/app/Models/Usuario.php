<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $table = 'usuario';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'apellidos',
        'contrasena',
        'email',
        'fecha_nacimiento',
        'activo',
        'fecha_registro',
        'api_token'
    ];
}
