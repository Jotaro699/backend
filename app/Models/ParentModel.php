<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'user_id', 'profession', 'cin', 'etudiant_cne'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class, 'cne', 'etudiant_cne');
    }
}

