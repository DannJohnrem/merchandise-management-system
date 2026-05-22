<?php

namespace Database\Factories;

use Laravel\Pail\File;
use App\Models\FixedAsset;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FixedAsset>
 */
class FixedAssetFactory extends Factory
{
    protected $model = FixedAsset::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_tag' => 'HP-' . strtoupper(Str::random(8)),
            'category' => 'Laptop',
            'asset_name' => 'HP Laptop',
            'serial_number' => strtoupper(Str::random(12)),
            'brand' => 'HP',
            'model' => $this->faker->randomElement([
                'EliteBook 840 G8',
                'ProBook 450 G9',
                'Pavilion 15',
                'Victus 16',
            ]),
            'purchase_cost' => $this->faker->numberBetween(45000, 85000),
            'supplier' => $this->faker->randomElement([
                'HP Philippines',
                'Octagon',
                'VillMan',
                'PC Express',
            ]),
            'assigned_employee' => null,
            'department' => $this->faker->randomElement([
                'IT',
                'Finance',
                'HR',
                'Operations',
            ]),
            'asset_class' => 'IT Equipment',
            'location' => $this->faker->randomElement([
                'Head Office',
                'Warehouse',
                'Branch Office',
            ]),
            'status' => 'available',
            'condition' => $this->faker->randomElement(['new', 'good']),
            'purchase_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'warranty_expiration' => now()->addYears(3),
            'purchase_order_no' => 'PO-' . $this->faker->numberBetween(10000, 99999),
            'remarks' => 'Auto-generated HP laptop asset',
        ];
    }
}
