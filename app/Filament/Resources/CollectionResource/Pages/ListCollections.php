<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use App\Models\Collection;
use App\Models\User;
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
                            Forms\Components\Select::make('members')
                                ->multiple()
                                ->options(User::all()->pluck('name', 'id')) // Carregar opções
                                ->label('Members')
                                ->searchable()
                                ->getOptionLabelUsing(function ($value) {
                            $user = User::find($value);
                            return view('components.user-option', ['user' => $user]);
                        }),
                        ])
                        ->action(function (array $data): void {
                            $collection = Collection::create($data);
                            $collection->members()->sync($data['members'] ?? []);
                        }),
        ];
    }
}
