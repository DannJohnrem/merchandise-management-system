<?php

namespace Database\Seeders;

use App\Models\FixedAsset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FixedAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FixedAsset::factory()->count(100)->create();
    }
}
