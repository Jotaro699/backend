<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'etudiant_id',
        'matiere_id',
        'enseignant_id',
        'note',
        'numero',
    ];

    // Relation avec étudiant
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    // Relation avec matière
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    // Relation avec enseignant (user)
    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }
}
