<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use App\Models\User;
use Filament\Forms;
use getID3;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Title')
                ->required(),
            Forms\Components\FileUpload::make('file_path')
                ->disk('public')
                ->required()
                ->preserveFilenames()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        $filePath = $state->getRealPath();
                        $fileSize = $state->getSize();
                        $getID3 = new getID3;
                        $fileInfo = $getID3->analyze($filePath);
                        $duration = isset($fileInfo['playtime_seconds']) ? (int)$fileInfo['playtime_seconds'] : 0;

                        $set('size', $fileSize);
                        $set('duration', $duration);
                        $set('status', 'ready');
                    }
                }),
            Forms\Components\Hidden::make('size'),
            Forms\Components\Hidden::make('duration'),
            Forms\Components\Hidden::make('status')->default('processing'),
            Forms\Components\Hidden::make('user_id')->default(auth()->id()),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            //Tables\Columns\ImageColumn::make('thumbnail')->label('Thumbnail')->disk('public')->path('thumbnails'), // Precisa que as miniaturas estejam armazenadas no disco 'public' no diretório 'thumbnails'
            Tables\Columns\TextColumn::make('title')->label('Video'),
            Tables\Columns\TextColumn::make('file_path')->label('File Path'),
            Tables\Columns\TextColumn::make('formatted_duration')->label('Duração'),
            Tables\Columns\TextColumn::make('formatted_size')->label('Tamanho'),
            Tables\Columns\BadgeColumn::make('status')->label('Status')
            ->colors([
                'processing' => 'bg-yellow-500',
                'ready' => 'bg-green-500',
                'error' => 'bg-red-500',
            ]),
            Tables\Columns\ImageColumn::make('user.avatar')
                ->label('Avatar')
                ->circular()
                ->size(40),
            //Tables\Columns\TextColumn::make('user.name')->label('Uploaded by')
            //    ->formatStateUsing(function ($state) {
            //        return $state ? $state->name : '';
            //    }),
            Tables\Columns\TextColumn::make('created_at')->date()->label('Upload em'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
