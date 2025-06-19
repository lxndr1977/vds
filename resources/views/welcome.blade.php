<x-layouts.app :title="__('Inscrições Vem Dançar Sudamérica 2025')">

        <div class="px-4 md:px-6 lg:px-8 py-6 bg-secondary pt-18  text-white">

            <h1 class="mb-4 text-4xl font-bold uppercase text-center ">Inscrições Vem Dançar Sudamérica 2025</h1>
            <p class="text-lg mb-6 text-center">As inscrições estarão abertas de <strong>20 de junho a 14 de setembro de 2025.</strong> </p>
        </div>
         
        <div class="py-12">
            <div class="flex justify-center ">
                <div class="max-w-3xl mx-auto space-y-4">
                    <p>Estão abertas as inscrições para o Vem Dançar Sudamérica 2025! Será um prazer contar com a participação do seu  Grupo/Escola/Cia nessa edição.</p>
                    <p>Antes de iniciar a inscrição, recomendamos que você leia o Regulamento e que tenha em mãos as informações que serão solicitadas no formulário.</p>

                    <p>O formulário de inscrição é dividido em seis etapas:</p>
                    <ul class="list-decimal pl-4 mb-6 space-y-2 ">
                        <li>Dados da Grupo/Escola/Cia</li>
                        <li>Cadastro da Equipe Diretiva (Diretor e/ou Coordenadores)</li>
                        <li>Cadastro dos(as) coreógrafos(as) do Grupo/Escola/Cia</li>
                        <li>Cadastro dos(as) bailarino(as) do Grupo/Escola/Cia</li>
                        <li>Cadastro das Coreografias</li>
                        <li>Revisão e finalização da Inscrição</li>
                    </ul>
                   <x-mary-button link="{{ route('register') }}" class="btn-primary text-lg w-full py-6"
                    >
                     Iniciar Inscrição
                   </x-mary-button>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 md:px-6 lg:px-8 bg-secondary py-12">
        <h2 class="mb-8 text-2xl font-medium text-center text-white uppercase ">Apoio Cultural</h2>

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <img src="/images/logo-prefeitura-uruguaiana.png" alt="Logo Prefeitura de Uruguaiana">
            </div>  
            <div>
                <img src="/images/logo-secretaria-cultura.png" alt="Logo Secretaria de Cultura de Uruguaiana">
            </div>
            <div>
                <img src="/images/logo-secretaria-turismo.png" alt="Logo Secretaria de Turismo de Uruguaiana">
            </div>  
        </div>
    </div>
</x-layouts.app>
