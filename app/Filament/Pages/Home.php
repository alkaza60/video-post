<?php

namespace App\Filament\Pages;

use App\Models\Collection;
use App\Models\User;
use App\Models\Video;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Carbon\Carbon;

class Home extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.home';

    public $collections;
    public $recentUploads;
    public $totalStorage; // Gigabytes
    public $maxStorage = 50; // Terabytes
    public $totalBandwidth; // Terabytes
    public $maxBandwidth = 50; // Terabytes
    public $nextPaymentDate;

    public function mount()
    {
        $this->collections = Collection::with('videos', 'members')->get();
        $this->recentUploads = Video::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $this->totalStorage = $this->calculateTotalStorage();
        $this->totalBandwidth = $this->calculateTotalBandwidth();
        $this->nextPaymentDate = $this->calculateNextPaymentDate(auth()->user());
    }

    private function calculateTotalStorage()
    {
        $totalSizeInBytes = Video::sum('size');
        return $totalSizeInBytes / (1024 * 1024 * 1024); // Converter para Gigabytes
    }

    private function calculateTotalBandwidth()
    {
        // calcula a largura de banda total utilizada, se necessário.
        return 10; // Exemplo estático, em Terabytes
    }

    private function calculateNextPaymentDate(User $user)
    {
        return Carbon::parse($user->created_at)->addDays(30);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('createCollection')
                ->label('+ Create Collection')
                ->slideOver()
                ->modalWidth(MaxWidth::Medium)
                ->form([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\Textarea::make('description'),
                    Forms\Components\Select::make('members')
                        ->multiple()
                        ->options(User::all()->pluck('name', 'id'))
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
                    $this->mount(); // Recarregar as coleções
                }),
        ];
    }
}
