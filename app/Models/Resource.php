<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'capacidad'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'resource_id');
    }
}

