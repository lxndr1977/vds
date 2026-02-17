<?php

use App\Http\Controllers\RegistrationPrintController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['auth'])->get('/', function () {
    return view('site');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
//     Volt::route('settings/password', 'settings.password')->name('settings.password');
//     Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
// });

Route::middleware(['auth'])->get('/inscricao', function () {
    return view('site');
})->name('site');

Route::get('/registration/{record}/print', [RegistrationPrintController::class, 'print'])
    ->name('registration.print')
    ->middleware(['auth']);

Route::get('/inicio', function () {
    return view('welcome');
})->name('welcome');

require __DIR__.'/auth.php';
