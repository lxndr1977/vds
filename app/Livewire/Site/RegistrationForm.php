<?php

namespace App\Livewire\Site;

use App\Models\School;
use Livewire\Component;
use App\Models\Registration;
use App\Services\ZipCodeService;
use Illuminate\Support\Facades\DB;
use App\Livewire\Site\Traits\WithFinalStep;
use App\Livewire\Site\Traits\WithSchoolStep;
use App\Livewire\Site\Traits\WithDancersStep;
use App\Livewire\Site\Traits\WithMembersStep;
use App\Livewire\Site\Traits\WithChoreographyStep;
use App\Livewire\Site\Traits\WithChoreographersStep;

class RegistrationForm extends Component
{
    // Usando as traits para cada etapa do formulário
    use WithSchoolStep,
        WithMembersStep,
        WithChoreographersStep,
        WithDancersStep,
        WithChoreographyStep,
        WithFinalStep;

    public int $currentStep = 1;
    public int $totalSteps = 6;
    public ?School $school = null;
    public ?Registration $registration = null;

    public ?string $status = null;

    /**
     * Monta o componente, carregando ou inicializando os dados da inscrição.
     *
     * @return void
     */
    public function mount()
    {
        // Garante que a escola do usuário autenticado seja carregada ou uma nova seja criada
        // Isso evita que um usuário veja os dados de outro
        $this->school = School::firstOrNew([
            'user_id' => auth()->id()
        ]);

        // Se a escola já existir, carrega a inscrição associada (se houver)
        if ($this->school->exists) {
            if ($this->school->exists) {
                $this->registration = Registration::with([
                        'school.choreographies.dancers',
                        'school.choreographies.choreographyType',
                        // carregar membros relacionados se existirem
                        // ou carregar membros via escola, exemplo abaixo
                    ])->where('school_id', $this->school->id)->first();
            }

                $this->status = $this->registration?->status_registration;
        }

        // Inicializa os dados de cada etapa
        $this->mountSchoolStep();
        $this->mountMembersStep();
        $this->mountChoreographersStep();
        $this->mountDancersStep();
        $this->mountChoreographyStep();

        if ($this->status === 'finished') {
            $this->currentStep = $this->totalSteps;
        }
    }

    /**
     * Avança para a próxima etapa do formulário.
     *
     * @return void
     */
    public function nextStep()
    {
        //  if ($this->isFinished || $this->isCancelled) {
        //     return;
        // }

        // Validações específicas por etapa
        if ($this->currentStep === 2 && $this->members->isEmpty()) {
             $this->error(
                title: "Informações pendentes", 
                icon: 'o-information-circle', 
                description: 'É necessário adicionar pelo menos um integrante da equipe diretivaa para prosseguir.',
                position: 'toast-top toast-center',
                css: "bg-red-500 border-red-500 text-white text-md");

            return;
        }

        if ($this->currentStep === 3 && $this->choreographers->isEmpty()) {
              $this->error(
                title: "Informações pendentes", 
                icon: 'o-information-circle', 
                description: 'É necessário adicionar pelo menos um (a) coreógrafo(a) para prosseguir.',
                position: 'toast-top toast-center',
                css: "bg-red-500 border-red-500 text-white text-md");

            return;
        }

        if ($this->currentStep === 4 && $this->dancers->isEmpty()) {
              $this->error(
                title: "Informações pendentes", 
                icon: 'o-information-circle', 
                description: 'É necessário adicionar pelo menos um (a) bailarino(a) para prosseguir.',
                position: 'toast-top toast-center',
                css: "bg-red-500 border-red-500 text-white text-md");

            return;
        }

        if ($this->currentStep === 5) {
            if ($this->choreographies->isEmpty()) {
                $this->error(
                    title: "Informações pendentes", 
                    icon: 'o-information-circle', 
                    description: 'É necessário adicionar pelo menos uma coreografia para prosseguir.',
                    position: 'toast-top toast-center',
                    css: "bg-red-500 border-red-500 text-white text-md");

                return;
            }
            
            if (!$this->validateChoreographyParticipants()) {
                return; // Impede de avançar se a validação falhar
            }
        }

        
        $this->currentStep++;
        $this->dispatch('stepChanged');
    }

    /**
     * Retorna para a etapa anterior do formulário.
     *
     * @return void
     */
    public function previousStep()
    {
        // if ($this->isFinished || $this->isCancelled) {
        //     return;
        // }

        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('stepChanged');
        }
    }

    /**
     * Navega para uma etapa específica.
     *
     * @param int $step
     * @return void
     */
    public function goToStep(int $step)
    {
        // if ($this->isFinished || $this->isCancelled) {
        //     return;
        // }

        // Garante que o usuário só possa navegar para etapas já concluídas ou a atual
        if ($step <= $this->getHighestCompletedStep()) {
            $this->currentStep = $step;
            $this->dispatch('stepChanged');
        }
    }

    /**
     * Determina a etapa mais alta que o usuário já alcançou.
     * Isso pode ser usado para controlar a navegação na UI.
     *
     * @return int
     */
    private function getHighestCompletedStep(): int
    {
        if ($this->registration?->id && $this->choreographies->isNotEmpty()) return 5;
        if ($this->registration?->id && $this->dancers->isNotEmpty()) return 4;
        if ($this->registration?->id && $this->members->isNotEmpty()) return 3;
        if ($this->registration?->id) return 2;
        if ($this->school->exists) return 1;
        return 1;
    }


    /**
 * Valida se todas as coreografias têm a quantidade correta de bailarino(as).
 *
 * @return bool
 */
    private function validateChoreographyParticipants(): bool
    {
        foreach ($this->choreographies as $choreography) {
            $type = $this->choreographyTypes->firstWhere('id', $choreography->choreography_type_id);
            if (!$type) continue;

            $dancersCount = $choreography->dancers()->count();
            if ($dancersCount < $type->min_dancers || $dancersCount > $type->max_dancers) {
                  $this->error(
                    title: "Revise a quantidade de bailarino(as)", 
                    icon: 'o-information-circle', 
                    description: "A coreografia '{$choreography->name}' deve ter entre {$type->min_dancers} e {$type->max_dancers} bailarino(as).",
                    position: 'toast-top toast-center',
                    css: "bg-red-500 border-red-500 text-white text-md");

                    return false;
            }
        }

        return true;
    }


    /**
     * Renderiza o componente Livewire.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.site.registration-form');
    }
}