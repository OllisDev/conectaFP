<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Alumno;    // ← añadir
use App\Models\Profesor;  // ← añadir
use App\Models\Empresa;

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
        'telefono',
        'activo',
        'fecha_registro',
        'api_token'
    ];

    public function alumno()
    {
        return $this->hasOne(Alumno::class, 'id_usuario');
    }

    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'id_usuario');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id_usuario');
    }
}
