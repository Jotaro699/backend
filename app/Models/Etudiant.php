<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    protected $fillable = [
        'user_id', 'cne', 'classe', 'acte_naissance', 'photo_identite', 'parent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class); // لاحظ الاسم
    }
    
}

