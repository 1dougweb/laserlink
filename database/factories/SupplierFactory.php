<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyTypes = [
            'Materiais e Acabamentos Ltda',
            'Fornecimentos Industriais S.A.',
            'Distribuidora de Materiais Ltda',
            'Suprimentos Corporativos S.A.',
            'Materiais Premium Ltda',
            'Fornecimentos Especiais S.A.',
            'Distribuidora Nacional Ltda',
            'Suprimentos de Qualidade S.A.'
        ];

        $contactName = $this->faker->name();
        $companyName = $this->faker->company() . ' ' . $this->faker->randomElement($companyTypes);
        
        return [
            'name' => $contactName,
            'company_name' => $companyName,
            'cnpj' => $this->faker->optional(0.7)->numerify('##.###.###/####-##'),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'whatsapp' => $this->faker->optional(0.8)->phoneNumber(),
            'website' => $this->faker->optional(0.6)->url(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'zip_code' => $this->faker->postcode(),
            'notes' => $this->faker->optional(0.4)->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    /**
     * Estado para fornecedores inativos
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Estado para fornecedores premium
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Fornecedor Premium ' . $attributes['name'],
            'notes' => 'Fornecedor premium com materiais de alta qualidade e prazo de entrega garantido.',
        ]);
    }
}
