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
        'departamento',
        'telefono',
        'eliminado'
    ];
}
