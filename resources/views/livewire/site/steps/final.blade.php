{{-- Etapa 5: Revisão e Finalização --}}
<div x-data="{ openChoreographies: [] }" class="space-y-6">
   @if ($isFinished)
      <div class="bg-green-50 border border-green-100 text-green-600 rounded-lg shadow-md p-6 text-center mb-6">
         <x-mary-icon name="o-check-circle" class="w-12 h-12 mb-6" />
         <h2 class="text-2xl font-bold mb-4">Sua inscrição no Vem Dançar Sudamérica 2025 está confirmada!</h2>
         <p>Atualizada em {{ $registration->updated_at_brazilian }}</p>
      </div>
   @else
      <h2 class="text-2xl font-bold mb-4">Etapa 5: Revisão e Finalização</h2>
      <p class="text-gray-600 mb-6">Confira todos os dados da sua inscrição antes de finalizar.</p>
   @endif
   {{-- Stats Resumo --}}
   <div class="grid grid-cols-2 lg:grid-cols-{{ $showTotals ? '3' : '2' }} gap-4 mb-6">
      <x-mary-stat
         title="Particpantes"
         :value="$members->count() + $dancers->count() + $choreographers->count()"
         icon="o-users"
         color="text-primary-600"
         class="bg-zinc-50 border border-zinc-100 shadow" />

      <x-mary-stat
         title="Coreografias"
         :value="$choreographies->count()"
         icon="o-musical-note"
         color="text-primary-600"
         class="bg-zinc-50 border border-zinc-100 shadow" />

      @php
         $totalMemberFees = 0;
         $memberFeeDetails = [];

         foreach ($members as $member) {
             $fee = optional($member->memberType->getCurrentFee())->amount ?? 0;
             $totalMemberFees += $fee;
             $memberFeeDetails[] = [
                 'name' => $member->name,
                 'type' => $member->memberType->name ?? 'Desconhecido',
                 'fee' => $fee,
             ];
         }

         $totalChoreographyFees = 0;
         $choreographyFeeDetails = [];

         foreach ($choreographies as $choreography) {
             $chFee = optional($choreography->choreographyType->getCurrentFee())->amount ?? 0;
             $dancersCount = $choreography->dancers->count();
             $totalFeeForChoreography = $chFee * $dancersCount;
             $totalChoreographyFees += $totalFeeForChoreography;

             $choreographyFeeDetails[] = [
                 'name' => $choreography->name,
                 'type' => $choreography->choreographyType->name,
                 'fee_per_participant' => $chFee,
                 'participants_count' => $dancersCount,
                 'total' => $totalFeeForChoreography,
             ];
         }

         $extraFeesResult = \App\Models\ChoreographyExtraFee::calculateTotalFees($choreographies->count());
         $totalGeral = $totalMemberFees + $totalChoreographyFees + $extraFeesResult['total'];
      @endphp


      @if ($showTotals)
         <x-mary-stat
            title="Valor Total"
            :value="'R$ ' . number_format($totalGeral, 2, ',', '.')"
            icon="o-currency-dollar"
            color="text-primary-600"
            class="bg-zinc-50 border border-zinc-100 shadow col-span-1" />
      @endif
   </div>

   {{-- Dados da Escola --}}
   <x-mary-card
      title="Grupo/Escola/Cia"
      subtitle="Informações de contato do Grupo/Escola/Cia"
      class="border border-zinc-100 rounded-lg shadow">
      <p><strong>Nome:</strong> {{ $school->name }}</p>
      <p><strong>Endereço:</strong> 
         {{ $school->street }},
         {{ $school->number }}
         {{ $school->complement ? ', ' . $school->complement : '' }}, 
         {{ $school->district }},
         {{ $school->city }}/{{ $school->state }}</p>

      <p><strong>Responsável:</strong> {{ $school->responsible_name }}</p>
      <p><strong>Email do Responsável:</strong> {{ $school->responsible_email }}</p>
      <p><strong>Whatsapp do Responsável:</strong> {{ $school->responsible_phone }}</p>
   </x-mary-card>


   <x-mary-card
      title="Participantes"
      subtitle="Relação da Equipe Diretiva, Coreógrafos e Bailarinos que participarão do evento"
      class="border border-zinc-100 rounded-lg shadow">
      {{-- Acordeão Resumo da Equipe (Membros) --}}
      <x-mary-collapse  class="mb-6">
         <x-slot:heading>
            Equipe Diretiva ({{ $members->count() }})
         </x-slot:heading>
         <x-slot:content>
            @foreach ($members as $member)
               <x-mary-list-item :item="$member" value="name" sub-value="memberType.name" separator />
            @endforeach
         </x-slot:content>
      </x-mary-collapse>

      {{-- Acordeão Resumo dos Coreógrafos --}}
      <x-mary-collapse separator class="mb-6">
         <x-slot:heading>
            Coreógrafos ({{ $choreographers->count() }})
         </x-slot:heading>
         <x-slot:content>
            @foreach ($choreographers as $choreographer)
               <x-mary-list-item :item="$choreographer" value="name" sub-value="choreographer_types" separator />
            @endforeach
         </x-slot:content>
      </x-mary-collapse>

      {{-- Acordeão Resumo dos Dançarinos --}}
      <x-mary-collapse separator>
         <x-slot:heading>
            Bailarinos ({{ $dancers->count() }})
         </x-slot:heading>
         <x-slot:content>
            @foreach ($dancers as $dancer)
               <x-mary-list-item :item="$dancer" value="name" sub-value="age" separator />
            @endforeach
         </x-slot:content>
      </x-mary-collapse>
   </x-mary-card>

   {{-- Acordeão das Coreografias Inscritas --}}
   <x-mary-card
      title="Coreografias ({{ $choreographies->count() }})"
      subtitle="Relação das Coreografias inscritas no evento"
      class="border border-zinc-100 rounded-lg shadow">
      <div class="space-y-4">
         @forelse($choreographies as $index => $choreography)
            <x-mary-collapse>
               <x-slot:heading>
                  <div class="font-semibold text-lg">{{ $choreography->name }}</div>
                  <div class="mt-3 text-base flex flex-col md:flex-row gap-2">
                        <x-mary-badge value="Formação: {{ $choreography->choreographyType->name }}" class="badge-soft text-xs md:text-sm" />
                        <x-mary-badge value="Categoria: {{ $choreography->choreographyCategory->name }}" class="badge-soft text-xs md:text-sm" />
                        <x-mary-badge value="Modalidade: {{ $choreography->danceStyle->name }}" class="badge-soft text-xs md:text-sm" />
                  </div>
                  
               </x-slot:heading>
               <x-slot:content>
                  <div class="mt-2 space-y-8">
                     <div class="space-y-1 mb-6">
                        <p><strong>Projeto Social:</strong> {{ $choreography->is_social_project ? 'Sim' : 'Não'}}</p>
                        <p><strong>Projeto Universitário:</strong> {{ $choreography->is_university_project ? 'Sim' : 'Não'}}</p>
                        <p><strong>Música:</strong> {{ $choreography->music }}</p>
                        <p><strong>Compositor:</strong> {{ $choreography->music_composer }}</p>
                        <p><strong>Duração:</strong> {{ $choreography->duration }}</p>
                     </div>

                     <div>
                        <h4 class="font-semibold mb-2 uppercase">Coreógrafos</h4>
                        @if ($choreography->choreographers->count())
                        <div class="space-y-1 mb-6">
                           @foreach ($choreography->choreographers as $choreographer)
                           <x-mary-list-item :item="$choreographer" value="name" sub-value="choreographer_types" />
                           @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 italic">Sem coreógrafos cadastrados.</p>
                        @endif
                     </div>
                     <div>

                        <h4 class="font-semibold mb-2 uppercase">Dançarinos</h4>
                        @if ($choreography->dancers->count())
                        <div class="space-y-1">
                           @foreach ($choreography->dancers as $dancer)
                           <x-mary-list-item :item="$dancer" value="name" sub-value="age" />
                           @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 italic">Sem dançarinos cadastrados.</p>
                        @endif
                     </div>

                     <div class="text-sm font-normal">
                        <h4 class="font-semibold mb-2 uppercase">Total de participantes</h4>


                        @foreach($choreography->school->getMembersCountByType() as $typeName => $count)
                           <p>{{ $typeName }}es: {{ $count }} </p>
                        @endforeach

                        <p> Coreógrafos: {{ $choreography->choreographers->count() }}</p>

                        <p> Bailarinos: {{ $choreography->dancers->count() }}</p>

                     </div>
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

   @if ($showTotals)
      {{-- Resumo das Taxas --}}
      <x-mary-card title="Resumo das Taxas" class="border border-zinc-100 shadow mt-6">
         <div class="space-y-8">
            {{-- Taxa por Membro --}}
            <div>
               <h4 class="font-semibold mb-3">Taxa por Membro ({{ count($memberFeeDetails) }} membros)</h4>
               <div class="space-y-2">
                  @foreach ($memberFeeDetails as $detail)
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
                  <strong>Total taxa membros: R$ {{ number_format($totalMemberFees, 2, ',', '.') }}</strong>
               </div>
            </div>

            {{-- Taxas por Coreografia --}}
            <div>
               <h4 class="font-semibold mb-3">Taxas por Coreografia</h4>
               <div class="space-y-2">
                  @foreach ($choreographyFeeDetails as $detail)
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
                  <strong>Total taxa coreografias: R$ {{ number_format($totalChoreographyFees, 2, ',', '.') }}</strong>
               </div>
            </div>

            {{-- Taxas Extras --}}
            <div>
               <h4 class="font-semibold mb-3">Taxas Extras (por {{ $choreographies->count() }} coreografias)</h4>
               <div class="space-y-2">
                  @foreach ($extraFeesResult['fees'] as $extra)
                     <x-mary-card class="p-3 bg-zinc-50">
                        <div class="flex justify-between items-center">
                           <div>
                              <span class="font-medium">{{ $extra['description'] }}</span>
                              <div class="text-sm text-gray-600">
                                 R$ {{ number_format($extra['value_per_choreography'], 2, ',', '.') }} x
                                 {{ $extra['choreography_count'] }}
                              </div>
                           </div>
                           <span class="font-semibold">R$ {{ number_format($extra['total'], 2, ',', '.') }}</span>
                        </div>
                     </x-mary-card>
                  @endforeach
               </div>
               <div class="mt-3 p-3 bg-zinc-100 rounded-lg">
                  <strong>Total taxas extras: R$ {{ number_format($extraFeesResult['total'], 2, ',', '.') }}</strong>
               </div>
            </div>

            {{-- Total Geral --}}
            <div class="border-t pt-4">
               <x-mary-card class="bg-primary-50 border-primary-200">
                  <div class="text-center">
                     <h3 class="font-bold text-xl text-primary-800">
                        Valor Total da Inscrição: R$ {{ number_format($totalGeral, 2, ',', '.') }}
                     </h3>
                  </div>
               </x-mary-card>
            </div>
         </div>
      </x-mary-card>
   @endif

      
   {{-- Modal de Confirmação --}}
   <x-mary-modal wire:model="showConfirmationModal" title="Confirmar Finalização da Inscrição"
      subtitle="">
      <div class="space-y-4">
         <p class="text-zinc-900 text-sm">
            Deseja realmente finalizar a inscrição? 
         </p>
         
         <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-semibold text-blue-800 mb-2">Resumo da Inscrição:</h4>
            <ul class="text-sm text-blue-700 space-y-1">
               @foreach($choreography->school->getMembersCountByType() as $typeName => $count)
                  <li><strong>{{ $typeName }}es:</strong> {{ $count }} </li>
               @endforeach

               <li><strong> Coreógrafos:</strong> {{ $school->choreographers->count() }}</li>

               <li><strong> Bailarinos:</strong> {{ $school->dancers->count() }}</li>
               <li><strong>Coreografias:</strong> {{ $choreographies->count() }}</li>

               @if ($showTotals)
                  <li><strong>Valor Total:</strong> R$ {{ number_format($totalGeral, 2, ',', '.') }}</li>
               @endif
            </ul>
         </div>

       
      </div>

      <x-slot:actions>
         <x-mary-button
            label="Cancelar"
            @click="$wire.showConfirmationModal = false"
            class="btn-ghost" />
         <x-mary-button
            label="Sim, Finalizar"
            icon="o-check"
            class="btn-primary"
            wire:click="finishRegistration"
            spinner="finishRegistration" />
      </x-slot:actions>
   </x-mary-modal>

   <x-mary-modal wire:model="confirmDeleteModal" title="Confirmar exclusão" class="backdrop-blur">
    <div class="py-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.994-.833-2.464 0L3.349 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-900 font-medium">Tem certeza que deseja remover este Integrante?</p>
                <p class="text-gray-600 text-sm mt-1">Esta ação não pode ser desfeita.</p>
            </div>
        </div>
    </div>

    <x-slot:actions>
        <x-mary-button 
            icon="o-x-mark" 
            @click="$wire.confirmDeleteModal = false" 
        >
            Cancelar
        </x-mary-button>

        <x-mary-button 
            icon="o-trash" 
            wire:click="confirmRemoveMember"  
            class="btn-error" 
            spinner="confirmRemoveMember"
        >
            Remover
        </x-mary-button>
    </x-slot:actions>
</x-mary-modal>

<x-mary-modal wire:model="showReopenRegistrationModal" title="Reabrir a inscrição?" class="backdrop-blur">
    <div class="py-4">
        <div class="flex items-center gap-3 mb-4">
            <div>
                <p class="text-gray-900 font-medium">Deseja editar novamente a inscrição? Caso já tenha efetuado algum pagamento, entre em contato com a gerência após a concluir as alterações.</p>
            </div>
        </div>
    </div>

    <x-slot:actions>
        <x-mary-button 
            icon="o-x-mark" 
            @click="$wire.reopenRegistrationModal = false" 
        >
            Cancelar
        </x-mary-button>

        <x-mary-button 
            icon="o-pencil" 
            wire:click="reopenRegistration"  
            class="btn-primary" 
            spinner="reopenRegistration"
        >
            Editar
        </x-mary-button>
    </x-slot:actions>
   </x-mary-modal>


</div>

