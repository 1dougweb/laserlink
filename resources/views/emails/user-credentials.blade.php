<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados de Acesso - Laser Link</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #EE0000;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .credentials-box {
            background-color: white;
            border: 2px solid #EE0000;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
        .label {
            font-weight: bold;
            color: #EE0000;
        }
        .value {
            font-family: monospace;
            background-color: #e9e9e9;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }
        .button {
            display: inline-block;
            background-color: #EE0000;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laser Link</h1>
        <p>Dados de Acesso à Sua Conta</p>
    </div>
    
    <div class="content">
        <h2>Olá, {{ $user->name }}!</h2>
        
        <p>Uma conta foi criada automaticamente para você em nossa plataforma. Abaixo estão seus dados de acesso:</p>
        
        <div class="credentials-box">
            <h3>Dados de Acesso</h3>
            
            <div class="credential-item">
                <div class="label">E-mail:</div>
                <div class="value">{{ $user->email }}</div>
            </div>
            
            <div class="credential-item">
                <div class="label">Senha Temporária:</div>
                <div class="value">{{ $temporaryPassword }}</div>
            </div>
        </div>
        
        <p><strong>Importante:</strong> Por segurança, recomendamos que você altere sua senha no primeiro acesso.</p>
        
        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">Fazer Login</a>
        </div>
        
        <h3>O que você pode fazer com sua conta:</h3>
        <ul>
            <li>Acompanhar seus pedidos</li>
            <li>Visualizar histórico de orçamentos</li>
            <li>Salvar configurações de produtos</li>
            <li>Receber atualizações sobre seus pedidos</li>
        </ul>
        
        <p>Se você tiver alguma dúvida, entre em contato conosco através do WhatsApp ou e-mail.</p>
    </div>
    
    <div class="footer">
        <p>Este é um e-mail automático, por favor não responda.</p>
        <p>Laser Link - Comunicação Visual</p>
    </div>
</body>
</html>
