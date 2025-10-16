<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento {{ $budget->budget_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #EE0000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #EE0000;
            margin-bottom: 10px;
        }
        
        .company-info {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .budget-title {
            font-size: 20px;
            font-weight: bold;
            color: #EE0000;
            margin-top: 15px;
        }
        
        .budget-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .budget-details, .client-details {
            width: 48%;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #EE0000;
            margin-bottom: 10px;
            border-bottom: 1px solid #EE0000;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .items-table th {
            background-color: #EE0000;
            color: white;
            font-weight: bold;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .totals {
            width: 100%;
            margin-top: 20px;
        }
        
        .totals table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals td {
            padding: 5px 10px;
            border: none;
        }
        
        .totals .label {
            text-align: right;
            font-weight: bold;
            width: 70%;
        }
        
        .totals .value {
            text-align: right;
            width: 30%;
        }
        
        .total-row {
            border-top: 2px solid #EE0000;
            font-weight: bold;
            font-size: 14px;
        }
        
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f5f5f5;
            border-left: 4px solid #EE0000;
        }
        
        .notes-title {
            font-weight: bold;
            color: #EE0000;
            margin-bottom: 10px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-draft { background-color: #f3f4f6; color: #374151; }
        .status-sent { background-color: #dbeafe; color: #1e40af; }
        .status-approved { background-color: #dcfce7; color: #166534; }
        .status-rejected { background-color: #fecaca; color: #991b1b; }
        .status-expired { background-color: #fef3c7; color: #92400e; }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body { margin: 0; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">LASER LINK</div>
        <div class="company-info">Comunicação Visual e Soluções em Acrílico</div>
        <div class="company-info">CNPJ: 00.000.000/0001-00 | Tel: (11) 99999-9999</div>
        <div class="company-info">Email: contato@laserlink.com</div>
        <div class="company-info">www.laserlink.com</div>
        
        <div class="budget-title">ORÇAMENTO</div>
    </div>

    <!-- Budget and Client Information -->
    <div class="budget-info">
        <div class="budget-details">
            <div class="section-title">Dados do Orçamento</div>
            <div class="info-row">
                <span class="info-label">Número:</span>
                {{ $budget->budget_number }}
            </div>
            <div class="info-row">
                <span class="info-label">Data:</span>
                {{ $budget->created_at->format('d/m/Y') }}
            </div>
            <div class="info-row">
                <span class="info-label">Válido até:</span>
                {{ $budget->valid_until ? $budget->valid_until->format('d/m/Y') : 'Não especificado' }}
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge status-{{ $budget->status }}">
                    {{ $budget->status_label }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Criado por:</span>
                {{ $budget->user->name }}
            </div>
        </div>

        <div class="client-details">
            <div class="section-title">Dados do Cliente</div>
            <div class="info-row">
                <span class="info-label">Nome:</span>
                {{ $budget->client_name }}
            </div>
            @if($budget->client_company)
            <div class="info-row">
                <span class="info-label">Empresa:</span>
                {{ $budget->client_company }}
            </div>
            @endif
            @if($budget->client_email)
            <div class="info-row">
                <span class="info-label">Email:</span>
                {{ $budget->client_email }}
            </div>
            @endif
            @if($budget->client_phone)
            <div class="info-row">
                <span class="info-label">Telefone:</span>
                {{ $budget->client_phone }}
            </div>
            @endif
            @if($budget->client_address)
            <div class="info-row">
                <span class="info-label">Endereço:</span>
                {{ $budget->client_address }}
            </div>
            @endif
        </div>
    </div>

    <!-- Items Table -->
    <div class="section-title">Itens do Orçamento</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 40%;">Produto</th>
                <th style="width: 15%;">Quantidade</th>
                <th style="width: 15%;">Preço Unit.</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budget->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    {{ $item['product_name'] ?? 'Produto não especificado' }}
                    @if(isset($item['description']) && $item['description'])
                        <br><small style="color: #666;">{{ $item['description'] }}</small>
                    @endif
                </td>
                <td>{{ number_format($item['quantity'] ?? 0, 0, ',', '.') }}</td>
                <td>R$ {{ number_format($item['unit_price'] ?? 0, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($item['total'] ?? 0, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">R$ {{ number_format($budget->subtotal, 2, ',', '.') }}</td>
            </tr>
            @if($budget->discount_percentage > 0)
            <tr>
                <td class="label">Desconto ({{ number_format($budget->discount_percentage, 1) }}%):</td>
                <td class="value">- R$ {{ number_format($budget->discount_amount, 2, ',', '.') }}</td>
            </tr>
            @endif
            @if($budget->tax_percentage > 0)
            <tr>
                <td class="label">Taxa ({{ number_format($budget->tax_percentage, 1) }}%):</td>
                <td class="value">R$ {{ number_format($budget->tax_amount, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="label">TOTAL:</td>
                <td class="value">R$ {{ number_format($budget->total, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Notes -->
    @if($budget->notes)
    <div class="notes">
        <div class="notes-title">Observações:</div>
        <div>{{ $budget->notes }}</div>
    </div>
    @endif

    <!-- Terms -->
    @if($budget->terms)
    <div class="notes">
        <div class="notes-title">Termos e Condições:</div>
        <div>{{ $budget->terms }}</div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>LASER LINK</strong> - Comunicação Visual e Soluções em Acrílico</p>
        <p>Este orçamento é válido por {{ $budget->valid_until ? $budget->valid_until->diffInDays(now()) . ' dias' : '30 dias' }} a partir da data de emissão.</p>
        <p>Para dúvidas, entre em contato: contato@laserlink.com | (11) 99999-9999</p>
        <p>Gerado em {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
