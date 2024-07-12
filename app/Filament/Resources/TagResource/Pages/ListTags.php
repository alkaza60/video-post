<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use App\Models\Tag;
use Filament\Actions;
use Filament\Forms;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('createTag')
                        ->label('+Tags')
                        ->slideOver()
                        ->modalWidth(MaxWidth::Medium) // Define a largura do SlideOver
                        ->form([
                            Forms\Components\TextInput::make('name')->required(),
                            Forms\Components\TextInput::make('slug')->required(),
                        ])
                        ->action(function (array $data): void {
                            Tag::create($data);
                        }),
        ];
    }
}
