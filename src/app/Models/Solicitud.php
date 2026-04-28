<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitud';
    public $timestamps = false;

    protected $fillable = [
        'id_oferta',
        'id_alumno',
        'id_empresa',
        'id_profesor',
        'fecha_solicitud',
        'estado',
        'eliminado'
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'id_profesor');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }
}
