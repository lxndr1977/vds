# Inscrição Finalizada com Sucesso!

Olá {{ $registration->school->user->name ?? 'usuário' }},

Sua inscrição para a escola **{{ $registration->school->name }}** foi finalizada com sucesso!


<h1 class="font-bold text-5xl">Teste</h1>
Você pode revisar os detalhes da sua inscrição a qualquer momento acessando o painel de controle.

<a class="bg-primary-100 text-white p-8" href="https://vemdancarsudamerica.com.br">
Acessar Painel
</a>

Obrigado,
{{ config('app.name') }}