<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-mary-header 
        title="{{__('Create an account')}}" 
        subtitle="{{__('Enter your details below to create your account')}}"   
        class="mb-0" />

    <div class="-mt-12">

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="register" class="flex flex-col gap-3">
            <!-- Name -->
            <x-mary-input
                wire:model="name"
                :label="__('Name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <x-mary-input
                wire:model="email"
                :label="__('Email address')"   
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <x-mary-password
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
            />
    
            <!-- Confirm Password -->
            <x-mary-password
                wire:model="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end mt-2">
                <x-mary-button type="submit" class="btn-primary w-full">
                    {{ __('Create account') }}
                </x-mary-button>
            </div>
        </form>
    </div>

    <div class="flex flex-col space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600">
        {{ __('Already have an account?') }}
        <x-mary-button link="{{route('login')}}" class="btn-primary btn-soft w-full mt-2" wire:navigate>{{ __('Log in') }}</x-mary-button>
    </div>
</div>