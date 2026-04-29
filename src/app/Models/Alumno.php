<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumno';
    public $timestamps = false;
    protected $fillable = [
        'id_usuario',
        'id_profesor',
        'id_centro',
        'id_grado',
        'fecha_nacimiento',
        'curso',
        'cv',
        'dni',
        'disponibilidad',
        'eliminado'
    ];

    public function alumno()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');

    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'id_profesor');
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }

    public function centroEducativo()
    {
        return $this->belongsTo(CentroEducativo::class, 'id_centro');
    }
}
