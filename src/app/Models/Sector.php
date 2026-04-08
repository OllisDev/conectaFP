<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sector extends Model
{
    use HasFactory;

    protected $table = "sector";

    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];
}
