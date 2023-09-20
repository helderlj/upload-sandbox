<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
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
                        Textarea::make('description')
                            ->label('Descrição'),
                        FileUpload::make('file_path')
                            ->label('Arquivo')
                            ->live()
                            ->afterStateUpdated(function (?TemporaryUploadedFile $state, Set $set) {
                                $mime = mime_content_type($state->getPath()."/".$state->getFilename());
                                $size = $state->getSize();
                                $set('file_size', $size);
//                                dd($set);
                            })
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('custom-prefix-'),
                            )
                            ->required(),



                        Select::make('file_storage_type')
                            ->label('Tipo de armazenamento')
                            ->hidden(fn(Get $get): bool => !$get('file_path'))
                            ->required()
                            ->live()
                            ->options(fn(Get $get): array => match ($get('file_type')) {
                                'video' => [
                                    'local_storage' => 'Armazenamento Local',
                                    's3' => 'Armazenamento Remoto',
                                    'vimeo' => 'Vimeo (Apenas Videos)',
                                ],
                                default => [
                                    'local_storage' => 'Armazenamento Local',
                                    's3' => 'Armazenamento Remoto',
                                ],
                            }),

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
                        TextInput::make('file_type')
                            ->label('Tipo do arquivo')
                            ->disabled()
                            ->default(fn(?Content $record): string => $record?->file_type ?? '')
                            ->readOnly(),

                        TextInput::make('file_size')
                            ->label('Tamanho do arquivo')
                            ->disabled()
                            ->default(fn(?Content $record): string => $record?->file_size ?? '')
                            ->readOnly(),
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
