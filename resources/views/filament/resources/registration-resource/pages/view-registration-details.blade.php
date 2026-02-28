<x-filament-panels::page>
    <div class="space-y-6 text-gray-950 dark:text-white">
        {{-- Status and Top Actions --}}
        @if ($record->status_registration->value === 'finished')
            <x-filament::section>
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <x-filament::icon
                        icon="heroicon-o-check-circle"
                        class="h-12 w-12 text-success-600 dark:text-success-400"
                    />
                    <h2 class="mt-4 text-2xl font-bold tracking-tight">
                        Inscrição Confirmada!
                    </h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Esta inscrição foi finalizada com sucesso em {{ $record->updated_at->format('d/m/Y H:i') }}.
                    </p>
                </div>
            </x-filament::section>
        @endif

        {{-- Tabs Navigation --}}
        <x-filament::tabs label="Detalhes da Inscrição">
            <x-filament::tabs.item
                :active="$activeTab === 'summary'"
                wire:click="$set('activeTab', 'summary')"
                icon="heroicon-m-clipboard-document-list"
            >
                Resumo
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'school'"
                wire:click="$set('activeTab', 'school')"
                icon="heroicon-m-building-office-2"
            >
                Instituição
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'participants'"
                wire:click="$set('activeTab', 'participants')"
                icon="heroicon-m-users"
            >
                Participantes
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'choreographies'"
                wire:click="$set('activeTab', 'choreographies')"
                icon="heroicon-m-musical-note"
            >
                Coreografias
            </x-filament::tabs.item>
        </x-filament::tabs>

        {{-- Tab Content: Summary --}}
        <div x-show="$wire.activeTab === 'summary'" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Stat Card: Participants --}}
                <x-filament::section>
                    <div class="flex items-center gap-x-4">
                        <div class="rounded-lg bg-primary-100 p-3 dark:bg-primary-900/40">
                            <x-filament::icon
                                icon="heroicon-o-users"
                                class="h-6 w-6 text-primary-600 dark:text-primary-400"
                            />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Participantes</p>
                            <p class="text-2xl font-bold tracking-tight">
                                {{ ($record->school->members->count() ?? 0) + ($record->school->dancers->count() ?? 0) + ($record->school->choreographers->count() ?? 0) }}
                            </p>
                        </div>
                    </div>
                </x-filament::section>

                {{-- Stat Card: Choreographies --}}
                <x-filament::section>
                    <div class="flex items-center gap-x-4">
                        <div class="rounded-lg bg-info-100 p-3 dark:bg-info-900/40">
                            <x-filament::icon
                                icon="heroicon-o-musical-note"
                                class="h-6 w-6 text-info-600 dark:text-info-400"
                            />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Coreografias</p>
                            <p class="text-2xl font-bold tracking-tight">
                                {{ $record->school->choreographies->count() ?? 0 }}
                            </p>
                        </div>
                    </div>
                </x-filament::section>

                {{-- Stat Card: Status --}}
                <x-filament::section>
                    <div class="flex items-center gap-x-4">
                        <div class="rounded-lg bg-warning-100 p-3 dark:bg-warning-900/40">
                            <x-filament::icon
                                icon="heroicon-o-tag"
                                class="h-6 w-6 text-warning-600 dark:text-warning-400"
                            />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                            <p class="text-lg font-bold tracking-tight">
                                {{ $record->status_registration->getLabel() }}
                            </p>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            <x-filament::section heading="Visão Geral">
                <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                    <p>Esta é a página de visualização detalhada da inscrição para o **Vem Dançar Sudamérica 2025**. Navegue pelas abas acima para conferir os dados da instituição, a lista de participantes e as coreografias cadastradas.</p>
                </div>
            </x-filament::section>
        </div>

        {{-- Tab Content: School --}}
        <div x-show="$wire.activeTab === 'school'" x-cloak>
            <x-filament::section heading="Dados da Instituição">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</span>
                            <span class="font-semibold">{{ $record->school->name }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Responsável</span>
                            <span>{{ $record->school->responsible_name }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</span>
                            <span>{{ $record->school->responsible_email }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone/WhatsApp</span>
                            <span>{{ $record->school->responsible_phone }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                         <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Endereço</span>
                         <div class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg">
                            {{ $record->school->street }}, {{ $record->school->number }}<br>
                            @if($record->school->complement) {{ $record->school->complement }}<br> @endif
                            {{ $record->school->district }}<br>
                            {{ $record->school->city }} - {{ $record->school->state }}<br>
                            {{ $record->school->zip_code }}
                        </div>
                    </div>
                </div>
            </x-filament::section>
        </div>

        {{-- Tab Content: Participants --}}
        <div x-show="$wire.activeTab === 'participants'" x-cloak class="space-y-6">
            {{-- Equipe Diretiva --}}
            <x-filament::section collapsible>
                <x-slot name="heading">
                    <div class="flex items-center">
                         Equipe Diretiva
                        <x-filament::badge color="gray" size="sm" class="ml-2">
                            {{ $record->school->members->count() }}
                        </x-filament::badge>
                    </div>
                </x-slot>

                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($record->school->members as $member)
                        <div class="flex items-center justify-between py-3">
                            <div class="text-sm font-medium">
                                {{ $member->name }}
                            </div>
                            <x-filament::badge color="primary">
                                {{ $member->memberType->name ?? 'Não definido' }}
                            </x-filament::badge>
                        </div>
                    @empty
                        <p class="py-4 text-center text-sm text-gray-500 italic">Nenhum membro da equipe diretiva cadastrado.</p>
                    @endforelse
                </div>
            </x-filament::section>

            {{-- Coreógrafos --}}
            <x-filament::section collapsible>
                <x-slot name="heading">
                    <div class="flex items-center">
                        Coreógrafos
                        <x-filament::badge color="gray" size="sm" class="ml-2">
                            {{ $record->school->choreographers->count() }}
                        </x-filament::badge>
                    </div>
                </x-slot>

                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($record->school->choreographers as $choreographer)
                        <div class="flex items-center justify-between py-3">
                            <div class="text-sm font-medium">
                                {{ $choreographer->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $choreographer->choreographer_types }}
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-center text-sm text-gray-500 italic">Nenhum coreógrafo cadastrado.</p>
                    @endforelse
                </div>
            </x-filament::section>

            {{-- Bailarinos --}}
            <x-filament::section collapsible>
                <x-slot name="heading">
                    <div class="flex items-center">
                        Bailarinos
                        <x-filament::badge color="gray" size="sm" class="ml-2">
                            {{ $record->school->dancers->count() }}
                        </x-filament::badge>
                    </div>
                </x-slot>

                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($record->school->dancers as $dancer)
                        <div class="flex items-center justify-between py-3">
                            <div class="text-sm font-medium">
                                {{ $dancer->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $dancer->birth_date }}
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-center text-sm text-gray-500 italic">Nenhum bailarino cadastrado.</p>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        {{-- Tab Content: Choreographies --}}
        <div x-show="$wire.activeTab === 'choreographies'" x-cloak class="space-y-6">
            @forelse($record->school->choreographies as $choreography)
                <x-filament::section collapsible>
                    <x-slot name="heading">
                        <div class="flex flex-col md:flex-row md:items-center gap-3">
                            <span class="text-lg font-bold tracking-tight">{{ $choreography->name }}</span>
                            <div class="flex flex-wrap gap-2">
                                <x-filament::badge color="info" size="sm">
                                    {{ $choreography->choreographyType->name }}
                                </x-filament::badge>
                                <x-filament::badge color="warning" size="sm">
                                    {{ $choreography->choreographyCategory->name }}
                                </x-filament::badge>
                                <x-filament::badge color="success" size="sm">
                                    {{ $choreography->danceStyle->name }}
                                </x-filament::badge>
                            </div>
                        </div>
                    </x-slot>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        {{-- Basic Info --}}
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 border-b pb-1">Informações Técnicas</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Música:</span>
                                    <span>{{ $choreography->music }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Compositor:</span>
                                    <span>{{ $choreography->music_composer }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Duração:</span>
                                    <span>{{ $choreography->duration }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Projeto Social:</span>
                                    <span>{{ $choreography->is_social_project ? 'Sim' : 'Não' }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Projeto Universitário:</span>
                                    <span>{{ $choreography->is_university_project ? 'Sim' : 'Não' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Participants in this Choreography --}}
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 border-b pb-1">Escalado para esta Obra</h4>
                            
                            <div class="space-y-4">
                                {{-- Step Coreógrafos --}}
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 mb-2 underline decoration-primary-500/30">Coreógrafos ({{ $choreography->choreographers->count() }})</p>
                                    <div class="space-y-1">
                                        @foreach($choreography->choreographers as $choreographer)
                                            <div class="text-sm flex justify-between">
                                                <span>{{ $choreographer->name }}</span>
                                                <span class="text-gray-400 text-xs">{{ $choreographer->choreographer_types }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Step Bailarinos --}}
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 mb-2 underline decoration-info-500/30">Bailarinos ({{ $choreography->dancers->count() }})</p>
                                    <div class="max-h-40 overflow-y-auto pr-2 space-y-1">
                                        @foreach($choreography->dancers as $dancer)
                                            <div class="text-sm flex justify-between">
                                                <span>{{ $dancer->name }}</span>
                                                <span class="text-gray-400 text-xs">{{ $dancer->birth_date }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center text-gray-500">
                    <x-filament::icon
                        icon="heroicon-o-musical-note"
                        class="h-12 w-12 opacity-30"
                    />
                    <p class="mt-4 text-sm italic">Nenhuma coreografia cadastrada.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>