<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'title',
        'description',
        'slug',
        'author',
        'language',
        'is_published',
        'is_featured',
        'category_id',
        'created_by',
        'updated_by'
    ];
}
