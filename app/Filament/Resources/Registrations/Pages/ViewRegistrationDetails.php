<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Filament\Resources\Registrations\RegistrationResource;
use App\Models\Registration;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ViewRegistrationDetails extends Page
{
    protected static string $resource = RegistrationResource::class;

    public Registration $record;

    public $school;

    public $members;

    public $choreographers;

    public $dancers;

    public $choreographies;

    public $showTotals = true;

    public function getView(): string
    {
        return 'filament.resources.registration-resource.pages.view-registration-details';
    }

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
        if (! $this->school) {
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
    }

    public function getTitle(): string|Htmlable
    {
        $schoolName = $this->school ? $this->school->name : 'Escola não encontrada';

        return "Detalhes da Inscrição - {$schoolName}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Imprimir')
                ->icon('heroicon-o-printer')
                ->action('openPrintView')
                ->openUrlInNewTab()
                ->visible(fn () => $this->record && $this->record->exists && $this->choreographies->count() > 0),

            Actions\Action::make('back')
                ->label('Voltar')
                ->icon('heroicon-o-arrow-left')
                ->url(static::getResource()::getUrl('index')),

            Actions\Action::make('edit')
                ->label('Editar')
                ->icon('heroicon-o-pencil')
                ->url(static::getResource()::getUrl('edit', ['record' => $this->record]))
                ->visible(fn () => $this->record && $this->record->exists),
        ];
    }

    public function openPrintView()
    {
        $url = route('registration.print', ['record' => $this->record->id]);

        $this->js("window.open('$url', '_blank')");
    }

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl('index') => static::getResource()::getBreadcrumb(),
            '#' => $this->getTitle(),
        ];
    }
}
