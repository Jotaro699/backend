<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
protected $fillable = [
    'user_id', 'specialite', 'grade', 'cin', 'status', 'cv'
];
// protected $fillable = [
//     'user_id', 'specialite', 'grade', 'cin'
// ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

