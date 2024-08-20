<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\UploadedFile;
use Livewire\TemporaryUploadedFile;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;
}
