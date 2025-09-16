<div class="content bg-zinc-100 flex-1 flex flex-col h-auto lg:h-screen">
   <div class="flex-1 overflow-y-auto">
      <div class="p-6 md:p-8">
         <div class="max-w-4xl xl:max-w-6xl mx-auto pb-24 lg:pb-0">
            @if ($currentStep == 1 && $registrationsOpenToPublic)
               @include('livewire.site.steps.school')
            @elseif ($currentStep == 2 && $registrationsOpenToPublic)
               @include('livewire.site.steps.members')
            @elseif ($currentStep == 3 && $registrationsOpenToPublic)
               @include('livewire.site.steps.choreographers')
            @elseif ($currentStep == 4 && $registrationsOpenToPublic)
               @include('livewire.site.steps.dancers')
            @elseif ($currentStep == 5 && $registrationsOpenToPublic)
               @include('livewire.site.steps.choreography')
            @elseif ($currentStep == 6)
               @include('livewire.site.steps.final')
            @endif
         </div>
      </div>
   </div>

   @include('livewire.site.partials.registration-form-footer')
</div>