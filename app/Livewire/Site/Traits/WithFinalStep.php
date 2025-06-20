<?php

namespace App\Livewire\Site\Traits;

use App\Mail\RegistrationFinished;
// Certifique-se de que ChoreographyExtraFee está disponível e no namespace correto
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\ChoreographyExtraFee; 

trait WithFinalStep
{
    public $showConfirmationModal = false;

    public $showTotals = false;

    /**
     * Finaliza a inscrição, mudando seu status e salvando os dados.
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function finishRegistration()
    {
        if (!$this->registration) {
            $this->error(
                title: "Erro", 
                icon: "o-information-circle",
                description: 'Erro: Inscrição não encontrada.'); 

            return;
        }

        // Validação final (exemplo: garantir que há pelo menos uma coreografia)
        if ($this->choreographies->isEmpty()) {

            $this->error(
                title: "Coreografia não cadastrada", 
                icon: "o-information-circle",
                description: 'Você precisa cadastrar ao menos uma coreografia para finalizar.'); 

            $this->showConfirmationModal = false;
            $this->goToStep(4); // Volta para a etapa de coreografias
            return;
        }

       try {
            // Chama a nova função para obter os dados formatados
            $dataToSave = $this->getRegistrationDataForJson();

            // Salva o status e os dados JSON
            $this->registration->status_registration = 'finished';
            $this->registration->registration_data = $dataToSave;
            $this->registration->paid_amount = $dataToSave['financial_summary']['total_general'];

            $this->registration->save();

            // Fecha o modal
            $this->showConfirmationModal = false;

            $this->success(
                title: "Inscrição concluída", 
                icon: "o-information-circle",
                description: 'A sua inscrição para o evento foi finalizada com sucesso'
            ); 

        } catch (\Exception $e) {
            Log::error('Erro ao finalizar inscrição e salvar dados: ' . $e->getMessage());
            $this->showConfirmationModal = false;
            
            $this->error(title: 'Erro', icon: 'o-information-circle', description: $e->getMessage());
            return redirect()->route('site'); // Early return em caso de erro
        }

        // Tentativa de envio do email (fora do try-catch principal)
        if ($this->registration->school && $this->registration->school->user && $this->registration->school->user->email) {
            // try {
                Mail::to($this->registration->school->user->email)->send(new RegistrationFinished($this->registration));

                //  Mail::raw('Este é um e-mail de teste enviado pelo sistema.', function ($message) {
                //         $message->to('pereira.alexandre@gmail.com')
                //                 ->subject('Teste de envio de e-mail 2')
                //                 ->from('naoresponda@vemdancarsudamerica.com.br', 'Seu Nome ou Sistema');
                //     });


               //  Log::info('Email de confirmação enviado com sucesso para: ' . $this->registration->school->user->email);
            // } catch (\Exception $emailException) {
                // Log do erro mas não interrompe o fluxo
               //  Log::error('Falha no envio do email de confirmação: ' . $emailException->getMessage() . 
               //          ' - Inscrição ID: ' . $this->registration->id);
                
                        // dd($emailException->getMessage());
                // Opcional: Notificar o usuário sobre falha no email
                // $this->warning(title: 'Aviso', description: 'Inscrição realizada, mas houve problema no envio do email de confirmação');
            }
      //   } 

        return redirect()->route('site');

    }

    /**
     * Coleta e estrutura todos os dados da inscrição para serem salvos como JSON.
     *
     * @return array
     */
    protected function getRegistrationDataForJson(): array
    {
        $data = [
            'school' => [
                'name' => $this->school->name,
                'address' => [
                    'street' => $this->school->street,
                    'number' => $this->school->number,
                    'district' => $this->school->district,
                    'city' => $this->school->city,
                    'state' => $this->school->state,
                ],
            ],
            'members' => $this->members->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'role' => $member->role,
                    'member_type' => optional($member->memberType)->name,
                    'fee_amount' => optional(optional($member->memberType)->getCurrentFee())->amount ?? 0,
                ];
            })->toArray(),
            'choreographers' => $this->choreographers->map(function ($choreographer) {
                return [
                    'id' => $choreographer->id,
                    'name' => $choreographer->name,
                ];
            })->toArray(),
            'dancers' => $this->dancers->map(function ($dancer) {
                return [
                    'id' => $dancer->id,
                    'name' => $dancer->name,
                ];
            })->toArray(),
            'choreographies' => $this->choreographies->map(function ($choreography) {
                return [
                    'id' => $choreography->id,
                    'name' => $choreography->name,
                    'type' => optional($choreography->choreographyType)->name,
                    'min_dancers' => optional($choreography->choreographyType)->min_dancers,
                    'max_dancers' => optional($choreography->choreographyType)->max_dancers,
                    'fee_per_participant' => optional(optional($choreography->choreographyType)->getCurrentFee())->amount ?? 0,
                    'choreographers' => $choreography->choreographers->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toArray(),
                    'dancers' => $choreography->dancers->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->toArray(),
                ];
            })->toArray(),
            'financial_summary' => [
                'member_fees' => [],
                'choreography_fees' => [],
                'extra_fees' => [],
                'total_general' => 0,
                'total_member_fees' => 0,
                'total_choreography_fees' => 0,
                'total_extra_fees' => 0,
            ]
        ];

        // Cálculo das taxas
        $totalMemberFees = 0;
        foreach ($this->members as $member) {
            $fee = optional(optional($member->memberType)->getCurrentFee())->amount ?? 0;
            $totalMemberFees += $fee;
            $data['financial_summary']['member_fees'][] = [
                'name' => $member->name,
                'type' => optional($member->memberType)->name ?? 'Desconhecido',
                'fee' => $fee,
            ];
        }

        $totalChoreographyFees = 0;
        foreach ($this->choreographies as $choreography) {
            $chFee = optional(optional($choreography->choreographyType)->getCurrentFee())->amount ?? 0;
            $participantsCount = $choreography->dancers->count() + $choreography->choreographers->count();
            $totalFeeForChoreography = $chFee * $participantsCount;

            $totalChoreographyFees += $totalFeeForChoreography;

            $data['financial_summary']['choreography_fees'][] = [
                'name' => $choreography->name,
                'type' => optional($choreography->choreographyType)->name,
                'fee_per_participant' => $chFee,
                'participants_count' => $participantsCount,
                'total' => $totalFeeForChoreography,
            ];
        }

        // Importante: Certifique-se de que a classe ChoreographyExtraFee esteja importada
        // use App\Models\ChoreographyExtraFee; no topo do arquivo da trait.
        $extraFeesResult = ChoreographyExtraFee::calculateTotalFees($this->choreographies->count());
        $data['financial_summary']['extra_fees'] = $extraFeesResult['fees'];
        $data['financial_summary']['total_extra_fees'] = $extraFeesResult['total'];

        $data['financial_summary']['total_general'] = $totalMemberFees + $totalChoreographyFees + $extraFeesResult['total'];
        $data['financial_summary']['total_member_fees'] = $totalMemberFees;
        $data['financial_summary']['total_choreography_fees'] = $totalChoreographyFees;

        return $data;
    }

    public function teste()
    {
        Mail::raw('Este é um e-mail de teste enviado pelo sistema.', function ($message) {
            $message->to('pereira.alexandre@gmail.com')
                    ->subject('Teste de envio de e-mail')
                    ->from('naoresponda@vemdancarsudamerica.com.br', 'Seu Nome ou Sistema');
        });
    }

}