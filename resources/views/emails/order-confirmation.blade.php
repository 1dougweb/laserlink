<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Pedido - Laser Link</title>
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
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .order-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc2626;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .item:last-child {
            border-bottom: none;
        }
        .total {
            background: #dc2626;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }
        .whatsapp-btn {
            display: inline-block;
            background: #25d366;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
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
        <h1>🎉 Pedido Confirmado!</h1>
        <p>Obrigado por escolher a Laser Link</p>
    </div>

    <div class="content">
        <div class="order-info">
            <h2>📋 Informações do Pedido</h2>
            <p><strong>Número do Pedido:</strong> {{ $order->order_number }}</p>
            <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Status:</strong> Pendente</p>
        </div>

        <div class="order-info">
            <h2>👤 Dados do Cliente</h2>
            <p><strong>Nome:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            <p><strong>Telefone:</strong> {{ $order->customer_phone }}</p>
        </div>

        <div class="order-info">
            <h2>📍 Endereço de Entrega</h2>
            <p>{{ $order->shipping_street }}, {{ $order->shipping_number }}</p>
            @if($order->shipping_complement)
                <p>Complemento: {{ $order->shipping_complement }}</p>
            @endif
            <p>{{ $order->shipping_neighborhood }}</p>
            <p>{{ $order->shipping_city }} - {{ $order->shipping_state }}</p>
            <p>CEP: {{ $order->shipping_cep }}</p>
        </div>

        <div class="order-info">
            <h2>🛒 Itens do Pedido</h2>
            @foreach($order->items as $item)
                <div class="item">
                    <div>
                        <strong>{{ $item->product_name }}</strong><br>
                        <small>Quantidade: {{ $item->quantity }}</small>
                    </div>
                    <div>
                        <strong>R$ {{ number_format($item->total_price, 2, ',', '.') }}</strong>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="total">
            Subtotal: R$ {{ number_format($order->subtotal, 2, ',', '.') }}<br>
            @if($order->shipping_cost > 0)
                Frete: R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}<br>
            @endif
            <strong>TOTAL: R$ {{ number_format($order->total, 2, ',', '.') }}</strong>
        </div>

        @if($order->notes)
            <div class="order-info">
                <h2>📝 Observações</h2>
                <p>{{ $order->notes }}</p>
            </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ $whatsappUrl ?? 'https://wa.me/5511999999999' }}" class="whatsapp-btn">
                💬 Falar no WhatsApp
            </a>
        </div>

        <div class="footer">
            <p><strong>Laser Link</strong></p>
            <p>Especialistas em comunicação visual e produtos personalizados</p>
            <p>📱 (11) 99999-9999 | 📧 contato@laserlink.com.br</p>
            <p>Rua das Comunicações, 123 - Centro - São Paulo/SP</p>
        </div>
    </div>
</body>
</html>



