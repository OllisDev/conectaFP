<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profesor extends Model
{
    use HasFactory;
    protected $table = 'profesor';

    public $timestamps = false;
    protected $fillable = [
        'id_usuario',
        'id_centro',
        'id_grado',
        'id_departamento',
        'dni',
        'eliminado'
    ];

    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function alumno()
    {
        return $this->hasMany(Alumno::class, 'id_profesor');
    }
}
