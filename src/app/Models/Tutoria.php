<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tutoria extends Model
{
    use HasFactory;

    protected $table = 'tutoria';

    public $timestamps = false;

    protected $fillable = [
        'id_alumno',
        'id_profesor',
        'id_empresa',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'eliminado'
    ];
}
