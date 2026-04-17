<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
    use HasFactory;
    protected $table = 'empresa';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'descripcion',
        'nif',
        'id_sector',
        'direccion',
        'web',
        'activo'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
