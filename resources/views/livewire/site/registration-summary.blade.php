{{-- /resources/views/livewire/site/registration-summary.blade.php --}}

<div x-data="{ openChoreographies: [] }" class="space-y-6">
    <h2 class="text-2xl font-bold mb-4">Sua inscrição para o Vem Dançar Sudamérica foi recebida</h2>
    <p class="text-gray-600 mb-6">Caso ainda não tenha efetuado o pagamento, entre em contato com a gerência para confirmar a sua inscrição.</p>

    {{-- Stats Resumo --}}
    <div class="grid grid-cols-2 lg:grid-cols-{{ $showTotals ? '3' : '2' }} gap-4 mb-6">
        <x-mary-stat
            title="Participantes"
            :value="count($summaryData->registration_data['members']) + count($summaryData->registration_data['dancers']) + count($summaryData->registration_data['choreographers'])"
            icon="o-users"
            color="text-primary-600"
            class="bg-zinc-50 border border-zinc-100 shadow"
        />
        <x-mary-stat
            title="Coreografias"
            :value="count($summaryData->registration_data['choreographies'])"
            icon="o-musical-note"
            color="text-primary-600"
            class="bg-zinc-50 border border-zinc-100 shadow"
        />
        @if($showTotals)
        <x-mary-stat
            title="Valor Total"
            :value="'R$ ' . number_format($summaryData->registration_data['financial_summary']['total_general'], 2, ',', '.')"
            icon="o-currency-dollar"
            color="text-primary-600"
            class="bg-zinc-50 border border-zinc-100 shadow col-span-1"
        />
        @endif
    </div>

    {{-- Dados da Escola --}}
    <x-mary-card
        title="Escola"
        subtitle="Dados da escola, projeto ou companhia de dança"
        class="border border-zinc-100 rounded-lg shadow"
    >
        <p><strong>Nome:</strong> {{ $summaryData->registration_data['school']['name'] }}</p>
        <p><strong>Endereço:</strong> {{ $summaryData->registration_data['school']['address']['street'] }}, {{ $summaryData->registration_data['school']['address']['number'] }} - {{ $summaryData->registration_data['school']['address']['district'] }}, {{ $summaryData->registration_data['school']['address']['city'] }}/{{ $summaryData->registration_data['school']['address']['state'] }}</p>
    </x-mary-card>

    <x-mary-card
        title="Participantes"
        subtitle="Relação de membros, coreógrafos e dançarinos que participarão do evento"
        class="border border-zinc-100 rounded-lg shadow"
    >
        {{-- Membros --}}
        <x-mary-collapse separator class="mb-6">
            <x-slot:heading>
                Membros ({{ count($summaryData->registration_data['members']) }})
            </x-slot:heading>
            <x-slot:content>
                @foreach($summaryData->registration_data['members'] as $member)
                    <x-mary-list-item :item="$member" value="name" sub-value="member_type" separator />
                @endforeach
            </x-slot:content>
        </x-mary-collapse>

        {{-- Coreógrafos --}}
        <x-mary-collapse separator class="mb-6">
            <x-slot:heading>
                Coreógrafos ({{ count($summaryData->registration_data['choreographers']) }})
            </x-slot:heading>
            <x-slot:content>
                @foreach($summaryData->registration_data['choreographers'] as $choreographer)
                    <x-mary-list-item :item="$choreographer" value="name" separator />
                @endforeach
            </x-slot:content>
        </x-mary-collapse>

        {{-- Dançarinos --}}
        <x-mary-collapse separator>
            <x-slot:heading>
                Dançarinos ({{ count($summaryData->registration_data['dancers']) }})
            </x-slot:heading>
            <x-slot:content>
                @foreach($summaryData->registration_data['dancers'] as $dancer)
                    <x-mary-list-item :item="$dancer" value="name" separator />
                @endforeach
            </x-slot:content>
        </x-mary-collapse>
    </x-mary-card>

    {{-- Coreografias Inscritas --}}
    <x-mary-card
        title="Coreografias ({{ count($summaryData->registration_data['choreographies']) }})"
        subtitle="Relação das coreografias inscritas no evento"
        class="border border-zinc-100 rounded-lg shadow"
    >
        <div class="space-y-4">
            @forelse($summaryData->registration_data['choreographies'] as $choreography)
                <x-mary-collapse>
                    <x-slot:heading>
                        <div class="font-semibold">{{ $choreography['name'] }}</div>
                        <div class="mt-3 text-base space-x-2">
                            <x-mary-badge value="{{ $choreography['type'] }}" class="badge-soft" />
                        </div>
                    </x-slot:heading>
                    <x-slot:content>
                        <div class="mt-2">
                            <h4 class="text-md font-semibold mb-2">Coreógrafos ({{ count($choreography['choreographers']) }})</h4>
                            @if(count($choreography['choreographers']))
                                <div class="space-y-1 mb-6">
                                    @foreach($choreography['choreographers'] as $choreographerItem)
                                        <x-mary-list-item :item="$choreographerItem" value="name" />
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">Sem coreógrafos.</p>
                            @endif

                            <h4 class="font-semibold mb-2">Dançarinos ({{ count($choreography['dancers']) }})</h4>
                            @if(count($choreography['dancers']))
                                <div class="space-y-1">
                                    @foreach($choreography['dancers'] as $dancerItem)
                                        <x-mary-list-item :item="$dancerItem" value="name" />
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">Sem dançarinos.</p>
                            @endif
                        </div>
                    </x-slot:content>
                </x-mary-collapse>
            @empty
                <x-mary-card>
                    <p class="text-gray-500 italic">Nenhuma coreografia cadastrada.</p>
                </x-mary-card>
            @endforelse
        </div>
    </x-mary-card>

    @if($showTotals)

    {{-- Resumo das Taxas --}}
    <x-mary-card title="Resumo das Taxas" class="border border-zinc-100 shadow mt-6">
        @php
            $financials = $summaryData->registration_data['financial_summary'];
        @endphp
        <div class="space-y-8">
            {{-- Taxa por Membro --}}
            <div>
                <h4 class="font-semibold mb-3">Taxa por Membro ({{ count($financials['member_fees']) }} membros)</h4>
                <div class="space-y-2">
                    @foreach ($financials['member_fees'] as $detail)
                        <x-mary-card class="p-3 bg-zinc-50">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium">{{ $detail['name'] }}</span>
                                    <x-mary-badge value="{{ $detail['type'] }}" class="badge-soft ml-2" />
                                </div>
                                <span class="font-semibold">R$ {{ number_format($detail['fee'], 2, ',', '.') }}</span>
                            </div>
                        </x-mary-card>
                    @endforeach
                </div>
                <div class="mt-3 p-3 bg-zinc-100 rounded-lg">
                    <strong>Total taxa membros: R$ {{ number_format($financials['total_member_fees'], 2, ',', '.') }}</strong>
                </div>
            </div>

            {{-- Taxas por Coreografia --}}
            <div>
                <h4 class="font-semibold mb-3">Taxas por Coreografia</h4>
                <div class="space-y-2">
                    @foreach ($financials['choreography_fees'] as $detail)
                        <x-mary-card class="p-3 bg-zinc-50">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $detail['name'] }}</span>
                                        <x-mary-badge value="{{ $detail['type'] }}" class="ml-2 badge-soft" />
                                    </div>
                                    <span class="font-semibold">R$ {{ number_format($detail['total'], 2, ',', '.') }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Taxa por dançarino: R$ {{ number_format($detail['fee_per_participant'], 2, ',', '.') }} •
                                    Dançarinos: {{ $detail['participants_count'] }}
                                </div>
                            </div>
                        </x-mary-card>
                    @endforeach
                </div>
                <div class="mt-3 p-3 bg-zinc-100 rounded-lg">
                    <strong>Total taxa coreografias: R$ {{ number_format($financials['total_choreography_fees'], 2, ',', '.') }}</strong>
                </div>
            </div>

            {{-- Taxas Extras --}}
            @if(!empty($financials['extra_fees']))
            <div>
                <h4 class="font-semibold mb-3">Taxas Extras</h4>
                <div class="space-y-2">
                    @foreach ($financials['extra_fees'] as $extra)
                        <x-mary-card class="p-3 bg-zinc-50">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">{{ $extra['description'] }}</span>
                                <span class="font-semibold">R$ {{ number_format($extra['total'], 2, ',', '.') }}</span>
                            </div>
                        </x-mary-card>
                    @endforeach
                </div>
                <div class="mt-3 p-3 bg-zinc-100 rounded-lg">
                    <strong>Total taxas extras: R$ {{ number_format($financials['total_extra_fees'], 2, ',', '.') }}</strong>
                </div>
            </div>
            @endif

            {{-- Total Geral --}}
            <div class="border-t pt-4">
                <x-mary-card class="bg-primary-50 border-primary-200">
                    <div class="text-center">
                        <h3 class="font-bold text-xl text-primary-800">
                            Valor Total da Inscrição: R$ {{ number_format($financials['total_general'], 2, ',', '.') }}
                        </h3>
                    </div>
                </x-mary-card>
            </div>
        </div>
    </x-mary-card>
    @endif
   
</div>