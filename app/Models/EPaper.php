<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EPaper extends Model
{
    protected $table="epapers";

    use HasFactory;

    protected $fillable = [
        'image',
        'page_no',
        'publish_date',
        'created_by',
        'updated_by',
    ];
}
