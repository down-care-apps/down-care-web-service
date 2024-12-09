<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    // add fillables
    protected $fillable = [
        'category',
        'content',
        'date',
        'thumbnailURL',
        'title',
    ];

    // Boot function to handle UUID generation
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->id = (string) Str::uuid(); // Generate UUID
        });
    }
}
