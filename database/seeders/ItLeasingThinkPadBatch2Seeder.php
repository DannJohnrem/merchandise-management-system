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

        // ── 23 ThinkPad E14 Gen 4 ──
        $e14Laptops = [
            'PF66L81W',  'PF66M64S',  'PF66LBE4',  'PF66LBC0',
            'PF66L7Z5',  'PF66NKLJ',  'PF66M66J',  'PF676WT7',
            'PF6771PP',  'PF676850',  'PF676RLP',  'PF676WL8',
            'PF676WVD',  'PF676WM5',  'PF676J18',  'PF676WS3',
            'PF676HJT',  'PF6766TV',  'PF6766SZ',
            'PF6766VH',  'PF67684C',  'PF677685N',
        ];

        $e14Chargers = [
            '8SGX21J75549A1WH61L4291',  '8SGX21J75547L1CZ5BL08PS',  '8SGX21J75549A1WH61N4298',  '8SGX21J75549A1WH61L4257',
            '8SGX21J75549A1WH61N4338',  '8SGX21J75549A1WH61N581',   '8SGX21J75549A1WH61N4308',  '8SGX21J75547L1CZ61K18MB',
            '8SGX21J75547L1CZ61K1MLR',  '8SGX21J75547L1CZ61K1JBA',  '8SGX21J75547L1CZ61K1ML1',  '8SGX21J75547L1CZ61K1MKX',
            '8SGX21J75547L1CZ61K18R7',  '8SGX21J75547L1CZ5CR0939',  '8SGX21J75547L1CZ61K1ML3',  '8SGX21J75547L1CZ5941JDE',
            '8SGX21J75547L1CZ61K1MM4',  '8SGX21J75547L1CZ61K1E27',  '8SGX21J75547L1CZ61K1ML5',
            '8SGX21J75547L1CZ61K1JBS',  '8SGX21J75547L1CZ61K1JCR',  '8SGX21J75547L1CZ61K1MLY',
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

        // ── 21 ThinkPad E16 Gen 3 ──
        $e16Laptops = [
            'PF68446N',  'PF683JKT',  'PF6835DY',  'PF683YJR',
            'PF683JN1',  'PF67BL0A',  'PF6835G3',  'PF67BD6D',
            'PF683CLQ',  'PF683JNY',  'PF67B44B',  'PF6835HS',
            'PF6835H1',  'PF683JMA',  'PF67BDAL',  'PF67BD9X',
            'PF67BD7D',  'PF67BAZ4',  'PF67BB0X',  'PF67BAYD',
            'PF67BFFT',
        ];

        $e16Chargers = [
            '8SGX21J75547L1CZ63G2M5T',  '8SGX21J75547L1CZ63G2M6K',  '8SGX21J75547L1CZ63G094R',  '8SGX21J75547L1CZ63G2M65',
            '8SGX21J75547L1CZ63G2M6X',  '8SGX21J75588AEWH5CCKEN6',   '8SGX21J75547L1CZ63G09SV',  '8SGX21J75588AEWH5CCKENE',
            '8SGX21J75547L1CZ63G2M0T',  '8SGX21J75547L1CZ63G09SK',   '8SGX21J75588AEWH5CCKENG',  '8SGX21J75547L1CZ63G2M6T',
            '8SGX21J75547L1CZ63G09SM',  '8SGX21J75547L1CZ63G2M0N',   '8SGX21J75588AEWH5CCKE89',  '8SGX21J75588AEWH5CCH0YR',
            '8SGX21J75588AEWH5CCH0Z7',  '8SGX21J75588AEWH5CCKEN1',   '8SGX21J75588AEWH5CCKEN3',  '8SGX21J75588AEWH5CCKEMS',
            '8SGX21J75588AEWH5CCH0ZB',
        ];

        foreach ($e16Laptops as $i => $sn) {
            ItLeasing::create([
                'category'              => 'Laptop',
                'item_name'             => 'ThinkPad e16',
                'serial_number'         => $sn,
                'charger_serial_number' => $e16Chargers[$i],
                'brand'                 => 'Lenovo',
                'model'                 => 'ThinkPad E16 Gen 3',
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

        $this->command->info('✅ Seeded 44 ThinkPad units (23 E14 Gen 4 + 21 E16 Gen 3) successfully.');
    }
}
