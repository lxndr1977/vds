<x-mail::message>
# Inscrição Finalizada com Sucesso!

Olá {{ $registration->school->user->name ?? 'usuário' }},

Sua inscrição para a escola **{{ $registration->school->name }}** foi finalizada com sucesso!

**Detalhes da Inscrição:**
@if($registration->registration_data && isset($registration->registration_data['financial_summary']['total_general']))
- **Valor Total:** R$ {{ number_format($registration->registration_data['financial_summary']['total_general'], 2, ',', '.') }}
@endif
- Status: **{{ $registration->status_registration }}**

Você pode revisar os detalhes da sua inscrição a qualquer momento acessando o painel de controle.

<x-mail::button :url="route('dashboard')">
Acessar Painel
</x-mail::button>

Obrigado,
{{ config('app.name') }}
</x-mail::message>