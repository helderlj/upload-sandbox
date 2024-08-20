<?php

namespace App\Models;

use App\Models\Traits\FileDeleting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use SoftDeletes, HasFactory, FileDeleting;
    protected $fillable = [
        'name',
        'file_type',
        'file_path_remote',
        'file_path_local',
        'vimeo_video_id',
        'file_storage_type',
        'file_size',
        'description',
    ];
}
