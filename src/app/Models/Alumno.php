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
        'grado',
        'curso',
        'cv_url',
        'disponiblidad',
        'eliminado'
    ];
}
