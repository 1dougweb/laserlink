<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar fornecedores se não existirem
        $supplierAcrilico = Supplier::firstOrCreate(
            ['cnpj' => '12.345.678/0001-90'],
            [
                'name' => 'Carlos Silva',
                'company_name' => 'AcriPlast Materiais Ltda',
                'email' => 'contato@acriplast.com.br',
                'phone' => '(11) 3456-7890',
                'whatsapp' => '(11) 98765-4321',
                'website' => 'https://www.acriplast.com.br',
                'address' => 'Rua dos Acrílicos, 1000',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01234-567',
                'notes' => 'Fornecedor especializado em acrílico e plásticos',
                'is_active' => true,
            ]
        );

        $supplierMDF = Supplier::firstOrCreate(
            ['cnpj' => '23.456.789/0001-01'],
            [
                'name' => 'Maria Oliveira',
                'company_name' => 'MDF Premium Distribuidora S.A.',
                'email' => 'vendas@mdfpremium.com.br',
                'phone' => '(11) 2345-6789',
                'whatsapp' => '(11) 97654-3210',
                'website' => 'https://www.mdfpremium.com.br',
                'address' => 'Av. Industrial, 2500',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '02345-678',
                'notes' => 'Fornecedor de MDF e chapas de madeira',
                'is_active' => true,
            ]
        );

        $supplierTintas = Supplier::firstOrCreate(
            ['cnpj' => '34.567.890/0001-12'],
            [
                'name' => 'João Santos',
                'company_name' => 'ColorPrint Tintas e Vernizes Ltda',
                'email' => 'suporte@colorprint.com.br',
                'phone' => '(11) 3567-8901',
                'whatsapp' => '(11) 96543-2109',
                'address' => 'Rua das Tintas, 750',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '03456-789',
                'notes' => 'Tintas UV e materiais de impressão',
                'is_active' => true,
            ]
        );

        $supplierEletrico = Supplier::firstOrCreate(
            ['cnpj' => '45.678.901/0001-23'],
            [
                'name' => 'Ana Costa',
                'company_name' => 'LED Tech Iluminação S.A.',
                'email' => 'comercial@ledtech.com.br',
                'phone' => '(11) 4567-8901',
                'whatsapp' => '(11) 95432-1098',
                'website' => 'https://www.ledtech.com.br',
                'address' => 'Av. da Tecnologia, 3000',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '04567-890',
                'notes' => 'Componentes elétricos e LED',
                'is_active' => true,
            ]
        );

        $supplierAdesivos = Supplier::firstOrCreate(
            ['cnpj' => '56.789.012/0001-34'],
            [
                'name' => 'Pedro Almeida',
                'company_name' => 'AdhesivePro Materiais Gráficos Ltda',
                'email' => 'atendimento@adhesivepro.com.br',
                'phone' => '(11) 5678-9012',
                'whatsapp' => '(11) 94321-0987',
                'address' => 'Rua dos Adesivos, 500',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '05678-901',
                'notes' => 'Adesivos e vinis especiais',
                'is_active' => true,
            ]
        );

        // 10 Matérias-primas específicas para comunicação visual
        $rawMaterials = [
            [
                'supplier_id' => $supplierAcrilico->id,
                'name' => 'Acrílico Cristal 3mm',
                'code' => 'ACR-CRI-3MM',
                'category' => 'acrilico',
                'unit' => 'm2',
                'stock_quantity' => 150.00,
                'stock_min' => 30.00,
                'stock_max' => 300.00,
                'unit_cost' => 85.50,
                'description' => 'Acrílico cristal transparente de 3mm de espessura',
                'specifications' => 'Chapa 2000x3000mm. Alta transparência, resistente a impactos. Ideal para displays, placas e proteções.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierAcrilico->id,
                'name' => 'Acrílico Leitoso 5mm',
                'code' => 'ACR-LEI-5MM',
                'category' => 'acrilico',
                'unit' => 'm2',
                'stock_quantity' => 80.00,
                'stock_min' => 20.00,
                'stock_max' => 200.00,
                'unit_cost' => 125.00,
                'description' => 'Acrílico leitoso opaco de 5mm',
                'specifications' => 'Chapa 2000x3000mm. Cor branca leitosa, ótimo para retroiluminação. Usado em letreiros e luminosos.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierMDF->id,
                'name' => 'MDF Cru 6mm',
                'code' => 'MDF-CRU-6MM',
                'category' => 'mdf',
                'unit' => 'm2',
                'stock_quantity' => 200.00,
                'stock_min' => 50.00,
                'stock_max' => 400.00,
                'unit_cost' => 35.00,
                'description' => 'MDF cru sem revestimento 6mm',
                'specifications' => 'Chapa 2750x1850mm. Densidade média, fácil corte a laser. Ideal para troféus, placas e displays.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierMDF->id,
                'name' => 'MDF Branco 3mm',
                'code' => 'MDF-BRA-3MM',
                'category' => 'mdf',
                'unit' => 'm2',
                'stock_quantity' => 120.00,
                'stock_min' => 30.00,
                'stock_max' => 250.00,
                'unit_cost' => 42.00,
                'description' => 'MDF branco pintado 3mm',
                'specifications' => 'Chapa 2750x1850mm. Revestido branco em ambos os lados. Acabamento liso, pronto para uso.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierAcrilico->id,
                'name' => 'PS Cristal 2mm',
                'code' => 'PS-CRI-2MM',
                'category' => 'ps',
                'unit' => 'm2',
                'stock_quantity' => 100.00,
                'stock_min' => 25.00,
                'stock_max' => 200.00,
                'unit_cost' => 28.50,
                'description' => 'Poliestireno cristal 2mm',
                'specifications' => 'Chapa 2000x1000mm. Material econômico, transparente. Alternativa ao acrílico para displays.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierTintas->id,
                'name' => 'Tinta UV Branca',
                'code' => 'TNT-UV-BRA',
                'category' => 'tinta',
                'unit' => 'l',
                'stock_quantity' => 25.00,
                'stock_min' => 5.00,
                'stock_max' => 50.00,
                'unit_cost' => 180.00,
                'description' => 'Tinta UV branca para impressão digital',
                'specifications' => 'Galão de 1 litro. Secagem instantânea sob luz UV. Alta cobertura e durabilidade.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierTintas->id,
                'name' => 'Verniz UV Brilhante',
                'code' => 'VER-UV-BRI',
                'category' => 'tinta',
                'unit' => 'l',
                'stock_quantity' => 15.00,
                'stock_min' => 3.00,
                'stock_max' => 30.00,
                'unit_cost' => 220.00,
                'description' => 'Verniz UV para acabamento brilhante',
                'specifications' => 'Galão de 1 litro. Proteção UV, acabamento espelhado. Aplicação em acrílico e MDF.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierEletrico->id,
                'name' => 'LED Strip 5050 Branco Frio',
                'code' => 'LED-5050-BF',
                'category' => 'eletrico',
                'unit' => 'un',
                'stock_quantity' => 50,
                'stock_min' => 10,
                'stock_max' => 100,
                'unit_cost' => 45.00,
                'description' => 'Fita LED 5050 branco frio 12V',
                'specifications' => 'Rolo de 5 metros. 60 LEDs por metro, 6000K. Consumo 12W/m. Para letreiros e luminosos.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierEletrico->id,
                'name' => 'Fonte 12V 10A',
                'code' => 'FNT-12V-10A',
                'category' => 'eletrico',
                'unit' => 'un',
                'stock_quantity' => 30,
                'stock_min' => 8,
                'stock_max' => 60,
                'unit_cost' => 95.00,
                'description' => 'Fonte chaveada 12V 10A',
                'specifications' => 'Fonte bivolt automática. Proteção contra curto-circuito. Para alimentação de LEDs.',
                'is_active' => true,
            ],
            [
                'supplier_id' => $supplierAdesivos->id,
                'name' => 'Vinil Adesivo Branco',
                'code' => 'VIN-ADE-BRA',
                'category' => 'adesivo',
                'unit' => 'm2',
                'stock_quantity' => 300.00,
                'stock_min' => 50.00,
                'stock_max' => 500.00,
                'unit_cost' => 18.00,
                'description' => 'Vinil adesivo branco fosco',
                'specifications' => 'Rolo 1,00x50m. Adesivo permanente, uso externo até 3 anos. Para recorte eletrônico.',
                'is_active' => true,
            ],
        ];

        foreach ($rawMaterials as $material) {
            RawMaterial::updateOrCreate(
                ['code' => $material['code']],
                $material
            );
        }

        $this->command->info('✅ 10 matérias-primas criadas com sucesso!');
        $this->command->info('📦 Total de matérias-primas no banco: ' . RawMaterial::count());
    }
}

