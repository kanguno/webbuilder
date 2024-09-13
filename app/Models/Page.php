<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'content'];

    // Definisikan relasi ke model Element
    public function elements()
    {
        return $this->hasMany(Element::class);
    }
}

