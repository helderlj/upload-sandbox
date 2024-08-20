<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Forms\Components\VimeoInput;
use App\Models\Content;
use Faker\Provider\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $slug = 'contents';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Group::make()->schema([
                        TextInput::make('name')
                            ->label('Titulo')
                            ->required(),
//                        Textarea::make('description')
//                            ->label('Descrição'),

                        Select::make('file_storage_type')
                            ->label('Tipo de armazenamento')
                            ->required()
                            ->live()
                            ->options([
                                'local_storage' => 'Armazenamento Local',
                                's3' => 'Armazenamento Remoto',
                                'vimeo' => 'Vimeo (Apenas Videos)',
                            ]),

                        FileUpload::make('file_path_remote')
                            ->label('Armazenamento Remoto')
                            ->hidden(function (Get $get): bool {
                                return $get('file_storage_type') !== 's3';
                            })
                            ->disk('digitalocean')
                            ->visibility('private')
                            ->directory('img/covers')
                            ->openable()
                            ->required(),

                        FileUpload::make('file_path_local')
                            ->label('Armazenamento Local')
                            ->hidden(fn(Get $get): bool => $get('file_storage_type') !== 'local_storage')
                            ->disk('local')
                            ->directory('public/img/covers')
                            ->required(),

                        FileUpload::make('vimeo_video_id')
                            ->label('Armazenamento Vimeo')
                            ->hidden(fn(Get $get): bool => $get('file_storage_type') !== 'vimeo')
                            ->disk('local')
                            ->directory('temp/vimeo')
                            ->required(),

//                        VimeoInput::make('file_path_vimeo')
//                            ->label('Armazenamento Vimeo')
//                            ->hidden(fn(Get $get): bool => $get('file_storage_type') !== 'vimeo')
//                            ->required(),


                    ]),
                ])->columnSpan(2),
                Section::make()->schema([
                    Group::make()->schema([
                        Placeholder::make('created_at')
                            ->label('Criado em')
                            ->content(fn(?Content $record): string => $record?->created_at?->diffForHumans() ?? '-'),
                        Placeholder::make('updated_at')
                            ->label('Ultima modificação em')
                            ->content(fn(?Content $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
//                        TextInput::make('file_type')
//                            ->label('Tipo do arquivo')
//                            ->formatStateUsing(fn(?string $state): string => Str::ucfirst($state))
//                            ->default(fn(?Content $record): string => $record?->file_type ?? '')
//                            ->readOnly(),
//                        TextInput::make('file_size')
//                            ->label('Tamanho do arquivo')
//                            ->default(fn(?Content $record): string => $record?->file_size ?? '')
//                            ->live()
//                            ->formatStateUsing(function ($state) {
//                                // converte size para human readble format
//                                $units = ['B', 'KB', 'MB', 'GB', 'TB'];
//                                for ($i = 0; $state > 1024; $i++) {
//                                    $state /= 1024;
//                                }
//                                return round($state, 2).' '.$units[$i];
//                            })
//                            ->readOnly(),
                    ]),

                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type'),

                TextColumn::make('size'),

                TextColumn::make('storage_location'),

                TextColumn::make('description'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
