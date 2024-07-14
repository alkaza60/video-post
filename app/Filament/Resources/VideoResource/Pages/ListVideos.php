<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Resources\VideoResource;
use App\Models\Video;
use Filament\Actions;
use Filament\Forms;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ListRecords;
use getID3;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;

    public $video;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('createVideo')
                ->label('Upload Video')
                ->slideOver()
                ->modalWidth(MaxWidth::Medium) // Define a largura do SlideOver
                ->form([
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
                    Forms\Components\Select::make('collection_id')
                        ->relationship('collection', 'name')
                        ->required()
                        ->label('Collection'),
                    Forms\Components\Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload()
                        ->label('Tags'),
                    Forms\Components\Hidden::make('size'),
                    Forms\Components\Hidden::make('duration'),
                    Forms\Components\Hidden::make('status')->default('processing'),
                    Forms\Components\Hidden::make('user_id')->default(auth()->id()),
                ])
                ->action(function (array $data): void {
                    $this->video = Video::create($data);
                    $this->video->tags()->sync($data['tags'] ?? []); // Sincronizar tags com o vídeo
                }),
        ];
    }
}
