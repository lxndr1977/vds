<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::get('/test-mail', function () {
    try {
        Mail::raw('Teste de email', function ($message) {
            $message->to('pereira.alexandre@gmail.com')
                   ->subject('Teste Laravel Umbler');
        });
        return 'Email enviado com sucesso!';
    } catch (Exception $e) {
        return 'Erro: ' . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
