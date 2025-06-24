<?php

namespace App\Filament\Resources\RegistrationResource\Pages;

use Filament\Actions;
use App\Models\Registration;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\RegistrationResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ViewRegistrationDetails extends Page
{
    protected static string $resource = RegistrationResource::class;
    protected static string $view = 'filament.resources.registration-resource.pages.view-registration-details';
    
    public Registration $record;
    public $school;
    public $members;
    public $choreographers;
    public $dancers;
    public $choreographies;
    public $showTotals = true;

    public function mount(Registration $record): void
    {
        // O Filament automaticamente injeta o modelo Registration via route model binding
        $this->record = $record->load([
            'school.choreographies',
            'school.choreographies.dancers',
            'school.choreographies.choreographers', 
            'school.choreographies.choreographyType',
            'school.choreographies.choreographyCategory',
            'school.choreographies.danceStyle',
            'school.members.memberType',
        ]);
        
        $this->loadData();
    }

    protected function loadData(): void
    {
        $this->school = $this->record->school;
        
        // Verifica se a escola existe
        if (!$this->school) {
            Log::warning('Escola não encontrada para o registro: ' . $this->record->id);
            $this->school = null;
            $this->members = collect();
            $this->choreographers = collect();
            $this->dancers = collect();
            $this->choreographies = collect();
            return;
        }
        
        // Carrega os dados da escola relacionados com verificações
        $this->members = $this->school->members ?? collect();
        $this->choreographers = $this->school->choreographers ?? collect();
        $this->dancers = $this->school->dancers ?? collect();
        $this->choreographies = $this->school->choreographies ?? collect();
        
        Log::info('Dados carregados - Membros: ' . $this->members->count() . 
                  ', Coreógrafos: ' . $this->choreographers->count() . 
                  ', Dançarinos: ' . $this->dancers->count() . 
                  ', Coreografias: ' . $this->choreographies->count());
    }

    public function getTitle(): string|Htmlable
    {
        $schoolName = $this->school ? $this->school->name : 'Escola não encontrada';
        return "Detalhes da Inscrição - {$schoolName}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Voltar')
                ->icon('heroicon-o-arrow-left')
                ->url(static::getResource()::getUrl('index')),
                
            Actions\Action::make('edit')
                ->label('Editar')
                ->icon('heroicon-o-pencil')
                ->url(static::getResource()::getUrl('edit', ['record' => $this->record]))
                ->visible(fn() => $this->record && $this->record->exists),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl('index') => static::getResource()::getBreadcrumb(),
            '#' => $this->getTitle(),
        ];
    }
}