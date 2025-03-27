<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jury extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description'
    ];

    public function projets()
    {
        return $this->belongsToMany(Projet::class);
    }

    public function member_jury()
    {
        return $this->hasMany(Member_jury::class);
    }
}
