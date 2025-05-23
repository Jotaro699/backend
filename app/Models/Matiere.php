<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'coefficient',
        'niveau',
        'classe',
        'enseignant_id',
    ];

    // relation avec enseignant
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    // relation avec cours (si كتستعمل cours)
    // public function cours()
    // {
    //     return $this->hasMany(Cours::class);
    // }
}


