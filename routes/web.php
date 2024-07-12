<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Membro\Home;
use App\Filament\Resources\VideoResource\Pages\ListVideos;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', Home::class);
