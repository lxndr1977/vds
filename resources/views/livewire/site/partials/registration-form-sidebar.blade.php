<div class="sidebar w-full md:w-80 xl:w-94 bg-secondary-600 text-white flex-shrink-0 h-auto lg:h-screen md:flex md:flex-col md:justify-between">
    <div class="p-6">
        <div class="hidden md:block mb-6">
            <div class="md:h-10 xl:h-16">
                <img src="/images/logo-vds-2025.png" alt="Logo Vem Dançar Sudamérica" width="900" height="156" class="max-h-8 xl:max-h-10 w-auto">
            </div>
        </div> 

        <h1 class="text-sm font-bold mb-4 hidden md:block mb-6 xl:mb-8">Inscrição VDS 2025</h1>
        
        <div class="flex flex-row justify-around md:flex-col space-y-1 xl:space-y-8">
            @foreach($this->steps as $stepNumber => $stepLabel)
                <div class="flex items-center space-x-3 {{ $currentStep == $stepNumber ? 'bg-secondary-700' : '' }} hover:bg-secondary-700 p-2 rounded-lg hover:cursor-pointer" data-step="{{ $stepNumber }}" wire:click="goToStep({{ $stepNumber }})">
                    <div class="w-8 h-8 {{ $currentStep >= $stepNumber ? 'bg-primary-600' : 'bg-secondary-500' }} rounded-full flex items-center justify-center text-sm font-semibold border-2 border-transparent">{{ $stepNumber }}</div>
                    <span class="font-medium hidden md:inline text-sm xl:text-base">{{ $stepLabel }}</span>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="p-6 hidden md:block mt-auto">
        <div class="bg-secondary-700 rounded-lg px-4 py-2">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-secondary-600 rounded-full flex items-center justify-center">
                    <x-mary-icon name="o-user" />
                </div>
                <div>
                    <p class="font-semibold text-sm line-clamp-1">{{ $userName }}</p>
                </div>
            </div>
        </div>
    </div>
</div>