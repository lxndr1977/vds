<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

use Mary\Traits\Toast;

new class extends Component {

    use Toast;

    public string $name = '';
    public string $email = '';

    public bool $showUserProfileModal = false;

    public function getListeners()
    {
        return ['openUserModalProfile' => 'openUserProfileModal'];
    }

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('userNameUpdated');

         $this->closeUserProfileModal();
      
        $this->success(
            title: 'Usuário Atualizado', 
            icon: 'o-check-circle', 
            description:'Os dados do usuário foram atualizados com sucesso',
            position: 'toast-top toast-right',
            css: "bg-green-100 border-green-100 text-green-900 text-md");   
      }     

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    public function openUserProfileModal()
    {
        $this->showUserProfileModal = true;
    }

    public function closeUserProfileModal()
    {
        $this->showUserProfileModal = false;
    }
}; ?>




<div>
   <x-mary-modal
      title="Editar Perfil"
      wire:model="showUserProfileModal"
      @close="$wire.closeUserProfileModal()">
      
      <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
         
         <x-mary-input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

         <div>
            <x-mary-input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
               
               <div>
               
                  <flux:text class="mt-4">
                     {{ __('Your email address is unverified.') }}

                     <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                        {{ __('Click here to re-send the verification email.') }}
                     </flux:link>
                  </flux:text>

                  @if (session('status') === 'verification-link-sent')
                     <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                     </flux:text>
                  @endif
               </div>

            @endif

         </div>

         <x-slot:actions>
            <x-mary-button 
                  icon="o-x-mark" 
                  @click="$wire.closeUserProfileModal()"
            >
                  Cancelar
            </x-mary-button>

            <x-mary-button
               icon="o-check"
               wire:click="updateProfileInformation"
               class="btn-primary"
               spinner="updateProfileInformation">
               Atualizar
            </x-mary-button>

         </x-slot:actions>

      </form>

   </x-mary-modal>

</div>
