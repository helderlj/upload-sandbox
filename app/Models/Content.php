<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'file_type',
        'file_path',
        'file_storage_type',
        'file_size',
        'description',
    ];
}
