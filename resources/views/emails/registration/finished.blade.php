<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inscrição Finalizada</title>
    <style>
        /* Reset básico para emails */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .email-header {
            background: linear-gradient(135deg, #b21653 0%, #8b1144 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .email-header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555555;
        }
        
        .success-message {
            background-color: #fdf2f8;
            border-left: 4px solid #b21653;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        
        .school-name {
            font-weight: bold;
            color: #b21653;
        }
        
        .test-heading {
            font-size: 48px;
            font-weight: bold;
            color: #dc2626;
            text-align: center;
            margin: 30px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .description {
            font-size: 16px;
            color: #666666;
            margin: 25px 0;
            line-height: 1.8;
        }
        
        .cta-container {
            text-align: center;
            margin: 40px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #b21653 0%, #8b1144 100%);
            color: white !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.2s ease;
            box-shadow: 0 4px 15px rgba(178, 22, 83, 0.3);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(178, 22, 83, 0.4);
        }
        
        .email-footer {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
        }
        
        .signature {
            font-size: 16px;
            margin-top: 20px;
        }
        
        .app-name {
            font-weight: bold;
            color: #b21653;
        }
        
        /* Responsividade */
        @media screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 4px;
            }
            
            .email-header {
                padding: 20px 15px;
            }
            
            .email-header h1 {
                font-size: 24px;
            }
            
            .email-body {
                padding: 30px 20px;
            }
            
            .test-heading {
                font-size: 36px;
            }
            
            .cta-button {
                padding: 14px 28px;
                font-size: 15px;
            }
        }
        
        /* Compatibilidade com clientes de email */
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Inscrição Finalizada com Sucesso!</h1>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Olá <strong>{{ $registration->school->user->name ?? 'usuário' }}</strong>,
            </div>
            
            <div class="success-message">
                Sua inscrição para a escola <span class="school-name">{{ $registration->school->name }}</span> foi finalizada com sucesso!
            </div>
            
            <div class="description">
                Você pode revisar os detalhes da sua inscrição a qualquer momento acessando o painel de controle.
            </div>
            
            <div class="cta-container">
                <a href="https://vemdancarsudamerica.com.br" class="cta-button">
                    Acessar Painel
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="signature">
                <span class="app-name">Vem Dançar Sudamérica 2025</span>
            </div>
        </div>
    </div>
</body>
</html>