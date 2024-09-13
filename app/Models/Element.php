<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    protected $fillable = [
        'name',
        'x',
        'y',
        'width',
        'height',
        'rotation',
        'z-index',
    ];

    // Definisikan relasi ke model Page
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
