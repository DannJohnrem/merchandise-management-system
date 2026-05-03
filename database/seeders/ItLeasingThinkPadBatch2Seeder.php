<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItLeasing;
use Carbon\Carbon;

class ItLeasingThinkPadBatch2Seeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ── 7 ThinkPad E14 Gen 4 (Batch 2) ──
        $e14Laptops = [
            'PF676J18', 'PF676WS3', 'PF676HKS',
            'PF676HJT', 'PF6766TV', 'PF6766SZ', 'PF6766VH',
        ];

        $e14Chargers = [
            '8SGX21J75547L1CZ61K1ML3', '8SGX21J75547L1CZ5941JDE', '8SGX21J75547L1CZ61K18S7',
            '8SGX21J75547L1CZ61K1MM4', '8SGX21J75547L1CZ61K1E27', '8SGX21J75547L1CZ61K1ML5', '8SGX21J75547L1CZ61K1JBS',
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
                'inclusions'            => ['Lenovo Bag', 'Lenovo Mouse'],
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
        }

        // ── 8 ThinkPad E16 Gen 3 (Batch 2) ──
        $e16Laptops = [
            'PF68446N', 'PF683JKT', 'PF6835DY', 'PF683YJR',
            'PF67BL0A', 'PF6835G3', 'PF67BD6D', 'PF67BFFT',
        ];

        $e16Chargers = [
            '8SGX21J75547L1CZ63G2M5T', '8SGX21J75547L1CZ63G2M6K', '8SGX21J75547L1CZ63G094R', '8SGX21J75547L1CZ63G2M65',
            '8SGX21J75588AEWH5CCKEN6', '8SGX21J75547L1CZ63G09SV', '8SGX21J75588AEWH5CCKENE', '8SGX21J75588AEWH5CCH0ZB',
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
                'inclusions'            => ['Lenovo Bag', 'Lenovo Mouse'],
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
        }

        $this->command->info('✅ Seeded 15 ThinkPad units Batch 2 (7 E14 + 8 E16) successfully.');
    }
}
