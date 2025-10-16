<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao {{ $siteName }}</title>
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
            background: linear-gradient(135deg, #EE0000 0%, #c41e3a 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .credentials {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .credentials strong {
            color: #856404;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #EE0000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background: #c41e3a;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="icon">🎉</div>
        <h1>Bem-vindo ao {{ $siteName }}!</h1>
    </div>
    
    <div class="content">
        <div class="card">
            <h2>Olá, {{ $user->name }}!</h2>
            
            <p>
                Seja muito bem-vindo(a) ao <strong>{{ $siteName }}</strong>! 
                Estamos muito felizes em tê-lo(a) conosco.
            </p>
            
            <p>
                Uma conta foi criada automaticamente para você durante seu pedido. 
                Agora você pode acompanhar seus pedidos, salvar endereços e muito mais!
            </p>
        </div>

        <div class="credentials">
            <h3 style="margin-top: 0;">📧 Suas Credenciais de Acesso</h3>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Senha Temporária:</strong> <code style="background: #fff; padding: 5px 10px; border-radius: 3px; font-size: 16px;">{{ $temporaryPassword }}</code></p>
            
            <p style="margin-top: 15px; color: #856404;">
                ⚠️ <strong>Importante:</strong> Por segurança, recomendamos que você altere sua senha no primeiro acesso.
            </p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">
                🔐 Fazer Login Agora
            </a>
        </div>

        <div class="card">
            <h3>O que você pode fazer com sua conta:</h3>
            <ul>
                <li>✅ Acompanhar seus pedidos em tempo real</li>
                <li>✅ Salvar endereços de entrega</li>
                <li>✅ Ver histórico de compras</li>
                <li>✅ Receber ofertas exclusivas</li>
                <li>✅ Finalizar compras mais rapidamente</li>
            </ul>
        </div>

        <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; border-radius: 4px; margin: 20px 0;">
            <p style="margin: 0;">
                <strong>💡 Dica:</strong> Adicione nosso email aos seus contatos para não perder nenhuma novidade!
            </p>
        </div>
    </div>

    <div class="footer">
        <p>
            <strong>{{ $siteName }}</strong><br>
            Especialistas em Acrílicos, Troféus, Medalhas, Placas e Letreiros
        </p>
        <p style="color: #999; font-size: 11px;">
            Este é um email automático, por favor não responda.
        </p>
    </div>
</body>
</html>

