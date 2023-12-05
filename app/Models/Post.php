<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    //campos de la tabla que vamos a permitir rellenar
    protected $fillable=['titulo', 'contenido', 'imagen', 'publicado'];
}
