<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grado extends Model
{
    use HasFactory;

    protected $table = 'grado';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'tipo',
        'familia_profesional',
        'codigo_grado'
    ];
}
