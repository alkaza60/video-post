<?php

namespace App\Filament\Pages;

use App\Models\Collection;
use App\Models\Video;
use Filament\Actions;
use Filament\Tables;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\MaxWidth;
use getID3;


class CollectionDetails extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $view = 'filament.pages.collection-details';
    
    // Remover da navegação
    protected static bool $shouldRegisterNavigation = false;

    public $collection;
    public $video;

    public function mount($record)
    {
        $this->collection = Collection::findOrFail($record);
    }

    protected function getTableQuery(): Builder
    {
        return Video::query()->where('collection_id', $this->collection->id);
    }

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
                    Forms\Components\Select::make('tags')
                        ->multiple()
                        ->relationship('tags', 'name')
                        ->label('Tags'),
                    Forms\Components\Hidden::make('size'),
                    Forms\Components\Hidden::make('duration'),
                    Forms\Components\Hidden::make('status')->default('processing'),
                    Forms\Components\Hidden::make('user_id')->default(auth()->id()),
                    Forms\Components\Hidden::make('collection_id')->default($this->collection->id), // Adicionar o collection_id automaticamente
                ])
                ->action(function (array $data): void {
                    $video = Video::create($data);
                    $video->tags()->sync($data['tags'] ?? []); // Sincronizar tags com o vídeo
                }),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            //Tables\Columns\ImageColumn::make('thumbnail')->label('Thumbnail')->disk('public')->path('thumbnails'), // Precisa que as miniaturas estejam armazenadas no disco 'public' no diretório 'thumbnails'
            Tables\Columns\TextColumn::make('title')->label('Title')->searchable(),
            Tables\Columns\TextColumn::make('formatted_duration')->label('Duração')->sortable(),
            Tables\Columns\TextColumn::make('formatted_size')->label('Tamanho')->sortable(),
            Tables\Columns\TextColumn::make('status')->label('Status')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->label('Uploaded At')->dateTime('d/m/Y H:i')->sortable(),
        ];
    }
}
