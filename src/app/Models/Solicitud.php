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
        'fecha_solicitud',
        'estado',
        'eliminado'
    ];
}
