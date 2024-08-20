<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_type')->nullable();
            $table->string('file_path_remote')->nullable();
            $table->string('file_path_local')->nullable();
            $table->string('vimeo_video_id')->nullable();
            $table->string('file_storage_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
