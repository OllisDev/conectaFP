<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignacion';

    public $timestamps = false;

    protected $fillable = [
        'id_alumno',
        'id_empresa',
        'id_profesor',
        'estado',
        'fecha_asignacion',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'id_profesor');
    }
}
