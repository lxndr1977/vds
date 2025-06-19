<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

use Mary\Traits\Toast;


new class extends Component {

    use Toast;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $showUserPasswordModal = false;

    public function getListeners()
    {
        return ['openUserPasswordModal' => 'openUserPasswordModal'];
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');

        $this->closeUserPasswordModal();
        
        $this->success(
            title: 'Senha Atualizada', 
            icon: 'o-check-circle', 
            description:'A senha usuário foi redefinida com sucesso',
            position: 'toast-top toast-right',
            css: "bg-green-100 border-green-100 text-green-900 text-md");   
      }

      public function openUserPasswordModal()
      {
         $this->showUserPasswordModal = true;
      }

      public function closeUserPasswordModal()
      {
         $this->showUserPasswordModal = false;
      }
}; ?>

<div>
   <x-mary-modal 
      title="Alterar Senha" 
      class="backdrop-blur"
      wire:model="showUserPasswordModal" 
      @close="$wire.closeUserPasswordModal()"
   >

   <form wire:submit="updatePassword" class="mt-6 space-y-6">
      <x-mary-input
            wire:model="current_password"
            :label="__('Current password')"
            type="password"
            required
            autocomplete="current-password"
      />
      <x-mary-input
            wire:model="password"
            :label="__('New password')"
            type="password"
            required
            autocomplete="new-password"
      />
      <x-mary-input
            wire:model="password_confirmation"
            :label="__('Confirm Password')"
            type="password"
            required
            autocomplete="new-password"
      />

      <x-slot:actions>
         <x-mary-button 
               icon="o-x-mark" 
               @click="$wire.closeUserPasswordModal()"
         >
               Cancelar
         </x-mary-button>

         <x-mary-button
            icon="o-check"
            wire:click="updatePassword"
            class="btn-primary"
            spinner="updatePassword">
            Atualizar
         </x-mary-button>

      </x-slot:actions>

   </form>

   </x-mary-modal>

</div>
