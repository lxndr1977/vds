{{-- resources/views/filament/resources/registration-resource/pages/view-registration-details.blade.php --}}

<x-filament-panels::page>

    {{-- Status da Inscrição --}}
    @if ($record->is_finished)
        <x-filament::section>
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-6 text-center">
                <x-heroicon-o-check-circle class="w-12 h-12 mx-auto mb-4 text-green-600" />
                <h2 class="text-2xl font-bold mb-2">Inscrição no Vem Dançar Sudamérica 2025 Confirmada!</h2>
                <p class="text-sm">Atualizada em {{ $record->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </x-filament::section>
    @endif

    {{-- Estatísticas Resumo --}}
    <x-filament::section>
        <x-slot name="heading">
            Resumo da Inscrição
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <x-heroicon-o-users class="w-8 h-8 mx-auto mb-2 text-blue-600" />
                <div class="text-2xl font-bold text-blue-900">
                    {{ $record->school->members->count() + $record->school->dancers->count() + $record->school->choreographers->count() }}
                </div>
                <div class="text-sm text-blue-700">Participantes</div>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                <x-heroicon-o-musical-note class="w-8 h-8 mx-auto mb-2 text-purple-600" />
                <div class="text-2xl font-bold text-purple-900">
                    {{ $record->school->choreographies->count() }}
                </div>
                <div class="text-sm text-purple-700">Coreografias</div>
            </div>

           <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                <x-heroicon-o-building-office class="w-8 h-8 mx-auto mb-2 text-gray-600" />
                <div class="text-sm text-gray-700 font-medium">Status</div>
                <div class="text-lg font-bold">
                    {{ $record->status_registration->getLabel() }}
                </div>
        </div>
    </x-filament::section>

    {{-- Dados da Escola --}}
    <x-filament::section>
        <x-slot name="heading">
            Grupo/Escola/Cia
        </x-slot>
        <x-slot name="description">
            Informações de contato do Grupo/Escola/Cia
        </x-slot>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $record->school->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Responsável</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $record->school->responsible_name }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email do Responsável</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $record->school->responsible_email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">WhatsApp do Responsável</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $record->school->responsible_phone }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Endereço</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $record->school->street }}, {{ $record->school->number }}
                    @if($record->school->complement), {{ $record->school->complement }}@endif, 
                    {{ $record->school->district }}, {{ $record->school->city }}/{{ $record->school->state }}
                </p>
            </div>
        </div>
    </x-filament::section>

    {{-- Participantes --}}
    <x-filament::section>
        <x-slot name="heading">
            Participantes
        </x-slot>
        <x-slot name="description">
            Relação da Equipe Diretiva, Coreógrafos e Bailarinos que participarão do evento
        </x-slot>

        <div class="space-y-6">
            {{-- Equipe Diretiva --}}
            <div class="border border-gray-200 rounded-lg">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Equipe Diretiva ({{ $record->school->members->count() }})
                    </h3>
                </div>
                <div class="p-4">
                    @forelse($record->school->members as $member)
                        <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <span class="font-medium">{{ $member->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">{{ $member->memberType->name ?? 'Não definido' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Nenhum membro da equipe diretiva cadastrado.</p>
                    @endforelse
                </div>
            </div>

            {{-- Coreógrafos --}}
            <div class="border border-gray-200 rounded-lg">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Coreógrafos ({{ $record->school->choreographers->count() }})
                    </h3>
                </div>
                <div class="p-4">
                    @forelse($record->school->choreographers as $choreographer)
                        <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <span class="font-medium">{{ $choreographer->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">{{ $choreographer->choreographer_types }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Nenhum coreógrafo cadastrado.</p>
                    @endforelse
                </div>
            </div>

            {{-- Bailarinos --}}
            <div class="border border-gray-200 rounded-lg">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Bailarinos ({{ $record->school->dancers->count() }})
                    </h3>
                </div>
                <div class="p-4">
                    @forelse($record->school->dancers as $dancer)
                        <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <span class="font-medium">{{ $dancer->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">{{ $dancer->age }} anos</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Nenhum bailarino cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- Coreografias --}}
    <x-filament::section>
        <x-slot name="heading">
            Coreografias ({{ $record->school->choreographies->count() }})
        </x-slot>
        <x-slot name="description">
            Relação das Coreografias inscritas no evento
        </x-slot>

        <div class="space-y-4">
            @forelse($record->school->choreographies as $choreography)
                <div class="border border-gray-200 rounded-lg">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $choreography->name }}</h3>
                            <div class="mt-2 md:mt-0 flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Formação: {{ $choreography->choreographyType->name }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Categoria: {{ $choreography->choreographyCategory->name }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Modalidade: {{ $choreography->danceStyle->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Informações da Coreografia --}}
                            <div class="space-y-3">
                                <h4 class="font-semibold text-gray-900 uppercase text-sm">Informações</h4>
                                <div class="space-y-2 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Projeto Social:</span>
                                        <span class="text-gray-900">{{ $choreography->is_social_project ? 'Sim' : 'Não' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Projeto Universitário:</span>
                                        <span class="text-gray-900">{{ $choreography->is_university_project ? 'Sim' : 'Não' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Música:</span>
                                        <span class="text-gray-900">{{ $choreography->music }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Compositor:</span>
                                        <span class="text-gray-900">{{ $choreography->music_composer }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Duração:</span>
                                        <span class="text-gray-900">{{ $choreography->duration }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Participantes da Coreografia --}}
                            <div class="space-y-3">
                                <h4 class="font-semibold text-gray-900 uppercase text-sm">Participantes</h4>
                                
                                {{-- Coreógrafos --}}
                                <div>
                                    <h5 class="font-medium text-gray-700 mb-2">Coreógrafos ({{ $choreography->choreographers->count() }})</h5>
                                    @if($choreography->choreographers->count())
                                        <div class="space-y-1">
                                            @foreach($choreography->choreographers as $choreographer)
                                                <div class="text-sm">
                                                    <span class="text-gray-900">{{ $choreographer->name }}</span>
                                                    <span class="text-gray-500">- {{ $choreographer->choreographer_types }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic text-sm">Sem coreógrafos cadastrados.</p>
                                    @endif
                                </div>

                                {{-- Dançarinos --}}
                                <div>
                                    <h5 class="font-medium text-gray-700 mb-2">Bailarinos ({{ $choreography->dancers->count() }})</h5>
                                    @if($choreography->dancers->count())
                                        <div class="space-y-1 max-h-32 overflow-y-auto">
                                            @foreach($choreography->dancers as $dancer)
                                                <div class="text-sm">
                                                    <span class="text-gray-900">{{ $dancer->name }}</span>
                                                    <span class="text-gray-500">- {{ $dancer->age }} anos</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic text-sm">Sem bailarinos cadastrados.</p>
                                    @endif
                                </div>

                                
                            </div>
                        </div>
                    </div>
                    {{-- Resumo de Participantes --}}
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <h5 class="font-medium text-gray-700 mb-2 text-sm">Total de Participantes</h5>
                                    <div class="text-sm space-y-1">
                                        @foreach($choreography->school->getMembersCountByType() as $typeName => $count)
                                            <div class="text-gray-600">{{ $typeName }}es: {{ $count }}</div>
                                        @endforeach
                                        <div class="text-gray-600">Coreógrafos: {{ $choreography->choreographers->count() }}</div>
                                        <div class="text-gray-600">Bailarinos: {{ $choreography->dancers->count() }}</div>
                                    </div>
                                </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <x-heroicon-o-musical-note class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                    <p class="text-gray-500 italic">Nenhuma coreografia cadastrada.</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>

</x-filament-panels::page>