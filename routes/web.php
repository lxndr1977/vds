<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth'])->get('/', function () {
    return view('site');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth'])->get('/inscricao', function () {
    return view('site');
})->name('site');

Route::get('/test-email', function() {
    $registration = \App\Models\Registration::with('school.user')->first();
    return view('emails.registration.finished', compact('registration'));
});

require __DIR__.'/auth.php';
