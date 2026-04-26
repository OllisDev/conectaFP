<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oferta extends Model
{
    use HasFactory;
    protected $table = 'oferta';
    public $timestamps = false;

    protected $fillable = [
        'id_empresa',
        'titulo',
        'descripcion',
        'requisitos',
        'modalidad',
        'fecha_publicacion',
        'estado',
        'eliminado'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'id_oferta');
    }
}
