<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CentroEducativo extends Model
{
    use HasFactory;
    protected $table = 'centro_educativo';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'localidad',
        'provincia',
        'codigo_centro'
    ];

}
