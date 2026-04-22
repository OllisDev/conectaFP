<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Valoracion extends Model
{
    use HasFactory;

    protected $table = 'valoracion';

    public $timestamps = false;

    protected $fillable = [
        'id_tutoria',
        'comentario',
        'fecha'
    ];
}
