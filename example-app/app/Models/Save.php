<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Save extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'publicacions_id',
        'title',
        'description'
    ];

    public function publicacion()
{
    return $this->belongsTo(Publicacion::class, 'publicacions_id');
}

}
