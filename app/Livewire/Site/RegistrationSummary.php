<?php

namespace App\Livewire\Site;

use App\Models\School;
use Livewire\Component;
use App\Models\Registration;
use App\Livewire\Site\Traits\WithFinalStep;

class RegistrationSummary extends Component
{
    use WithFinalStep;

    public $summaryData;
    public $isFinished;
    public $school;


    public $showTotals = false;
    
    public function mount()
    {
        
          $this->school = School::firstOrNew([
            'user_id' => auth()->id()
        ]);

        $this->summaryData =   Registration::select('registration_data')->with([
                    'school.choreographies.dancers',
                    'school.choreographies.choreographyType',
                    // carregar membros relacionados se existirem
                    // ou carregar membros via escola, exemplo abaixo
                ])->where('school_id', $this->school->id)->first();
    }

    public function render()
    {
        return view('livewire.site.registration-summary');
    }
}