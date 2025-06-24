<?php

use Livewire\Volt\Volt;
use App\Models\Registration;
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

// routes/web.php
Route::get('/test-view/{registration}', function (Registration $registration) {
    $record = $registration->load([
        'school',
        'school.members.memberType',
        'school.dancers',
        'school.choreographers',
        'school.choreographies.dancers',
        'school.choreographies.choreographers',
        'school.choreographies.choreographyType',
        'school.choreographies.choreographyCategory',
        'school.choreographies.danceStyle',
    ]);
    
    return view('filament.resources.registration-resource.pages.view-registration-details', compact('record'));
});

require __DIR__.'/auth.php';
