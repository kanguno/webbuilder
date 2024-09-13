<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UndanganBuilder;
use App\Livewire\PageBuilder;

// Rute Livewire harus dalam format ini
Route::get('/builder/{pageId}', UndanganBuilder::class)->name('undangan.builder');
Route::get('/builder', PageBuilder::class)->name('page.builder');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
