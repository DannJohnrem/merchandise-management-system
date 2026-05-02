<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItLeasing;
use Carbon\Carbon;

class ItLeasingThinkPadSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ── 10 ThinkPad E14 Gen 4 ──
        $e14Laptops = [
            'PF66L81B', 'PF66M697', 'PF66M65W', 'PF66M647',
            'PF66LBHW', 'PF66M67S', 'PF66M68B', 'PF676J0N',
            'PF676RFG', 'PF676WSN',
        ];

        $e14Chargers = [
            '8SGX21J75549A1WH61N4317', '8SGX21J75549A1WH61N4857',
            '8SGX21J75549A1WH61N4732', '8SGX21J75549A1WH61N4732',
            '8SGX21J75549A1WH61N4322', '8SGX21J75549A1WH61N5055',
            '8SGX21J75549A1WH61N4763', '8SGX21J75547L1CZ61K1ML6',
            '8SGX21J75547L1CZ61K1ML2', '8SGX21J75547L1CZ5941JE5',
        ];

        foreach ($e14Laptops as $i => $sn) {
            ItLeasing::create([
                'category'              => 'Laptop',
                'item_name'             => 'ThinkPad e14',
                'serial_number'         => $sn,
                'charger_serial_number' => $e14Chargers[$i],
                'brand'                 => 'Lenovo',
                'model'                 => 'ThinkPad E14 Gen 4',
                'purchase_cost'         => 48000.00,
                'rental_rate_per_month' => 3500.00,
                'supplier'              => 'Lenovo',
                'purchase_order_no'     => null,
                'purchase_date'         => '2026-04-14',
                'warranty_expiration'   => '2029-04-14',
                'assigned_company'      => 'BTSMC',
                'assigned_employee'     => 'BTSMC',
                'location'              => 'Spark Place, Quezon City',
                'status'                => 'deployed',
                'condition'             => 'new',
                'remarks'               => null,
                'inclusions' => json_encode(['Lenovo Bag', 'Lenovo Mouse']),
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
        }

        // ── 7 ThinkPad E16 Gen 3 ──
        $e16Laptops = [
            'PF683JJX', 'PF683CQS', 'PF67B8RD', 'PF67BB1C',
            'PF6835JE', 'PF683S81', 'PF67B45L',
        ];

        $e16Chargers = [
            '8SGX21J75547L1CZ63G2M6G', '8SGX21J75547L1CZ63G2M05',
            '8SGX21J75588AEWH5CCH0Z3', '8SGX21J75588AEWH5CCKEN9',
            '8SGX21J75547L1CZ63G2M5R', '8SGX21J75547L1CZ63G2M6L',
            '8SGX21J75588AEWH5CCKEMH',
        ];

        foreach ($e16Laptops as $i => $sn) {
            ItLeasing::create([
                'category'              => 'Laptop',
                'item_name'             => 'ThinkPad e16',
                'serial_number'         => $sn,
                'charger_serial_number' => $e16Chargers[$i],
                'brand'                 => 'Lenovo',
                'model'                 => 'Thinkpad E16 Gen 3',
                'purchase_cost'         => 48000.00,
                'rental_rate_per_month' => 3500.00,
                'supplier'              => 'Lenovo',
                'purchase_order_no'     => null,
                'purchase_date'         => '2026-04-14',
                'warranty_expiration'   => '2029-04-14',
                'assigned_company'      => 'BTSMC',
                'assigned_employee'     => 'BTSMC',
                'location'              => 'Spark Place, Quezon City',
                'status'                => 'deployed',
                'condition'             => 'new',
                'remarks'               => null,
                'inclusions' => json_encode(['Lenovo Bag', 'Lenovo Mouse']),
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
        }

        $this->command->info('✅ Seeded 17 ThinkPad units (10 E14 + 7 E16) successfully.');
    }
}
