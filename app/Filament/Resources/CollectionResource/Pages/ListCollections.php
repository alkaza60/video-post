<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use App\Models\Collection;
use Filament\Actions;
use Filament\Forms;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ListRecords;

class ListCollections extends ListRecords
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('createCollection')
                        ->label('+Collection')
                        ->slideOver()
                        ->modalWidth(MaxWidth::Medium) // Define a largura do SlideOver
                        ->form([
                            Forms\Components\TextInput::make('name')->required(),
                            Forms\Components\Textarea::make('description'),
                        ])
                        ->action(function (array $data): void {
                            Collection::create($data);
                        }),
        ];
    }
}
