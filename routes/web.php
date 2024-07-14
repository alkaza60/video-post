<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\CollectionDetails;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/collections/{record}', CollectionDetails::class)->name('collections.show');