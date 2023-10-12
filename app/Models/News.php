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
        'author',
        'isPublished',
        'isFeatured',
        'categoryId',
        'createdBy',
        'updatedBy'
    ];
}
