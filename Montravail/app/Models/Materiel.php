<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materiel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];

    // Relation : un matériel peut appartenir à plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
