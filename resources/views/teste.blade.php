<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Multistep</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 overflow-y-auto lg:overflow-hidden">
    <div class="flex flex-col md:flex-row min-h-screen overflow-y lg:overflow-hidden">
        <div class="sidebar w-full md:w-80 bg-purple-800 text-white flex-shrink-0 h-auto lg:h-screen md:flex md:flex-col md:justify-between">
            <div class="p-6">
                <div class="hidden md:block mb-8">
                    <div class="md:h-10 xl:h-16 bg-purple-700 rounded-lg flex items-center justify-center">
                        <span class="text-lg font-bold">SEU LOGO AQUI</span>
                    </div>
                </div>

                <h1 class="text-md lg:text-md font-bold mb-8 hidden md:block">Dashboard Formulário</h1>
                
                <div class="flex flex-row justify-around md:flex-col md:space-y-4">
                    <div class="step-indicator flex items-center space-x-3" data-step="1">
                        <div class="step-indicator-number w-8 h-8 bg-purple-400 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-transparent">1</div>
                        <span class="font-medium hidden md:inline md:text-sm lg:text-base">Dados Pessoais</span>
                    </div>
                    
                    <div class="step-indicator flex items-center space-x-3" data-step="2">
                        <div class="step-indicator-number w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-transparent">2</div>
                        <span class="font-medium hidden md:inline md:text-sm lg:text-base">Endereço</span>
                    </div>
                    
                    <div class="step-indicator flex items-center space-x-3" data-step="3">
                        <div class="step-indicator-number w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-transparent">3</div>
                        <span class="font-medium hidden md:inline md:text-sm lg:text-base">Contato</span>
                    </div>
                    
                    <div class="step-indicator flex items-center space-x-3" data-step="4">
                        <div class="step-indicator-number w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-transparent">4</div>
                        <span class="font-medium hidden md:inline md:text-sm lg:text-base">Profissional</span>
                    </div>
                    
                    <div class="step-indicator flex items-center space-x-3" data-step="5">
                         <div class="step-indicator-number w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-transparent">5</div>
                        <span class="font-medium hidden md:inline md:text-sm lg:text-base">Preferências</span>
                    </div>
                    
                    <div class="step-indicator flex items-center space-x-3" data-step="6">
                        <div class="step-indicator-number w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-transparent">6</div>
                        <span class="font-medium hidden md:inline md:text-sm lg:text-base">Confirmação</span>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-purple-700 hidden md:block mt-auto">
                <div class="bg-purple-700 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-sm">João Silva</p>
                            <p class="text-xs text-purple-300">Administrador</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Área de conteúdo com rodapé fixo -->
        <div class="content flex-1 flex flex-col h-auto lg:h-screen">
            <!-- Área de rolagem -->
            <div class="flex-1 overflow-y-auto">
                <div class="p-6 md:p-8">
                    <div class="max-w-4xl mx-auto">
                        <div class="form-step" data-step="1">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Dados Pessoais</h2>
                            <p class="text-sm md:text-base text-gray-600 mb-8">Preencha suas informações pessoais básicas</p>
                            <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Digite seu nome completo">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                                        <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">CPF</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="000.000.000-00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">RG</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Digite seu RG">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Digite seu nome completo">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                                        <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">CPF</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="000.000.000-00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">RG</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Digite seu RG">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Digite seu nome completo">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                                        <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">CPF</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="000.000.000-00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">RG</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Digite seu RG">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-step hidden" data-step="2">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Endereço</h2>
                            <p class="text-sm md:text-base text-gray-600 mb-8">Informe seu endereço residencial</p>
                            <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="00000-000">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Logradouro</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Rua, Avenida, etc.">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-step hidden" data-step="3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Contato</h2>
                            <p class="text-sm md:text-base text-gray-600 mb-8">Formas de contato e comunicação</p>
                            <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Principal</label>
                                        <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="seu@email.com">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Telefone Celular</label>
                                        <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="(11) 99999-9999">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-step hidden" data-step="4">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Informações Profissionais</h2>
                            <p class="text-sm md:text-base text-gray-600 mb-8">Dados sobre sua carreira e experiência</p>
                            <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Empresa Atual</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Nome da empresa">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Cargo</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm md:text-base" placeholder="Seu cargo atual">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-step hidden" data-step="5">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Preferências</h2>
                            <p class="text-sm md:text-base text-gray-600 mb-8">Configure suas preferências de uso</p>
                            <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-base md:text-lg font-semibold text-gray-700 mb-4">Notificações</h3>
                                        <div class="space-y-3">
                                            <label class="flex items-center"><input type="checkbox" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500"><span class="ml-3 text-sm text-gray-700">Receber notificações por email</span></label>
                                            <label class="flex items-center"><input type="checkbox" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500"><span class="ml-3 text-sm text-gray-700">Receber notificações por SMS</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-step hidden" data-step="6">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Confirmação</h2>
                            <p class="text-sm md:text-base text-gray-600 mb-8">Revise e confirme seus dados</p>
                            <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                                <p class="text-center text-gray-600">Todos os dados foram preenchidos corretamente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rodapé fixo -->
            <footer class="fixed bottom-0 left-0 w-full lg:relative bg-white border-t border-gray-200 p-4 mt-auto">
                <div class="max-w-4xl mx-auto flex items-center justify-between">
                    <button type="button" id="prev-btn" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm md:py-3 md:px-6">Anterior</button>
                    
                    <div class="hidden sm:flex flex-1 justify-center items-center space-x-2 mx-4">
                        <span class="text-xs sm:text-sm text-gray-600">Etapa</span>
                        <span class="text-xs sm:text-sm font-semibold text-purple-600" id="current-step-indicator">1</span>
                        <span class="text-xs sm:text-sm text-gray-600">de 6</span>
                        <div class="w-24 md:w-32 bg-gray-200 rounded-full h-2 ml-2 md:ml-4">
                            <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <button type="button" id="next-btn" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm md:py-3 md:px-6">Avançar</button>
                        <button type="button" id="finish-btn" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors hidden text-sm md:text-base md:py-3 md:px-6">Finalizar</button>
                    </div>
                </div>
            </footer>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const finishBtn = document.getElementById('finish-btn');
            
            const formSteps = document.querySelectorAll('.form-step');
            const stepIndicators = document.querySelectorAll('.step-indicator');
            const progressBar = document.getElementById('progress-bar');
            const currentStepIndicator = document.getElementById('current-step-indicator');

            let currentStep = 1;
            const totalSteps = formSteps.length;

            function updateForm() {
                formSteps.forEach(step => step.classList.add('hidden'));
                document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('hidden');
                
                stepIndicators.forEach(indicator => {
                    const stepNumberDiv = indicator.querySelector('.step-indicator-number');
                    const step = parseInt(indicator.dataset.step);
                    
                    stepNumberDiv.classList.remove('bg-white', 'text-purple-800', 'border-white', 'bg-purple-400', 'bg-purple-600');

                    if (step === currentStep) {
                        stepNumberDiv.classList.add('bg-white', 'text-purple-800', 'border-white');
                    } else if (step < currentStep) {
                        stepNumberDiv.classList.add('bg-purple-400');
                    } else {
                        stepNumberDiv.classList.add('bg-purple-600');
                    }
                });

                const progressPercentage = totalSteps > 1 ? ((currentStep - 1) / (totalSteps - 1)) * 100 : 0;
                progressBar.style.width = `${progressPercentage}%`;
                currentStepIndicator.textContent = currentStep;

                prevBtn.disabled = currentStep === 1;

                if (currentStep === totalSteps) {
                    nextBtn.classList.add('hidden');
                    finishBtn.classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    finishBtn.classList.add('hidden');
                }
            }

            nextBtn.addEventListener('click', () => {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateForm();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateForm();
                }
            });

            finishBtn.addEventListener('click', () => {
                alert('Cadastro finalizado com sucesso!');
            });
            
            updateForm();
        });
    </script>
</body>
</html>