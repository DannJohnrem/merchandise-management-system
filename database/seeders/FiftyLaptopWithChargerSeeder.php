<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FiftyLaptopWithChargerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['5CD53719WX', 'WRYDB0DGCLAGX7'],
            ['5CD5372JTH', 'WRYBF0A1RLA4SD'],
            ['5CD53719X3', 'WRYDB0DGCLAGX3'],
            ['5CD53719T0', 'WRYDB0DGCLAGXR'],
            ['5CD5372JTC', 'WRYBF0A1RLA4S6'],
            ['5CD53719SW', 'WRYDB0DGCLAA78'],
            ['5CD53719Q4', 'WRYDB0DGCLAPJP'],
            ['5CD53719WB', 'WRYDB0DGCLAPCY'],
            ['5CD53719SS', 'WRYDB0DGCLAPDK'],
            ['5CD53719WF', 'WRYDB0DGCLAPF4'],
            ['5CD53719TR', 'WRYDB0DGCLAGY2'],
            ['5CD53719TD', 'WRYDB0DGCLAA8D'],
            ['5CD53719WM', 'WRYDB0DGCLAPEA'],
            ['5CD53719TJ', 'WRYDB0DGCLAGY1'],
            ['5CD53719WS', 'WRYDB0DGCLAPF2'],
            ['5CD53719VZ', 'WRYDB0DGCLAGYH'],
            ['5CD53719V2', 'WRYDB0DGCLAPE8'],
            ['5CD53719SX', 'WRYDB0DGCLAPBN'],
            ['5CD53719LW', 'WRYDB0DGCLAPDA'],
            ['5CD53719VK', 'WRYDB0DGCLAPDO'],
            ['5CD53719Q3', 'WRYDB0DGCLAP6Q'],
            ['5CD53719TZ', 'WRYDB0DGCLAGXC'],
            ['5CD53719TV', 'WRYDB0DGCLAGYP'],
            ['5CD53719V0', 'WRYDB0DGCLAGXW'],
            ['5CD5372JSR', 'WRYBF0A1RLA4S8'],
            ['5CD53719W5', 'WRYDB0DGCLAGX9'],
            ['5CD53719WL', 'WRYDB0DGCLAPCU'],
            ['5CD53719T1', 'WRYDB0DGCLAP7E'],
            ['5CD53719VM', 'WRYDB0DGCLAGXH'],
            ['5CD53719WG', 'WRYDB0DGCLAP7N'],
            ['5CD5372JT4', 'WRYBF0A1RLA4RB'],
            ['5CD53719W9', 'WRYDB0DGCLAGXP'],
            ['5CD53719MW', 'WRYDB0DGCLAP6O'],
            ['5CD53719SG', 'WRYDB0DGCLAGXF'],
            ['5CD53719TP', 'WRYDB0DGCLAP6S'],
            ['5CD53719W2', 'WRYDB0DGCLAPFC'],
            ['5CD53719TM', 'WRYDB0DGCLAGXD'],
            ['5CD5372JTJ', 'WRYBF0A1RLA4RF'],
            ['5CD53719SZ', 'WRYDB0DGCLAP6R'],
            ['5CD53719WD', 'WRYDB0DGCLAGYM'],
            ['5CD53719PF', 'WRYDB0DGCLAP03'],
            ['5CD53719V1', 'WRYDB0DGCLAGYR'],
            ['5CD53719QG', 'WRYDB0DGCLAPCX'],
            ['5CD53719LS', 'WRYDB0DGCLAPCW'],
            ['5CD53719Q0', 'WRYDB0DGCLAPDI'],
            ['5CD53719MT', 'WRYDB0DGCLAPEC'],
            ['5CD53719VN', 'WRYDB0DGCLAGXT'],
            ['5CD53719X4', 'WRYDB0DGCLAPB9'],
            ['5CD53719Q2', 'WRYDB0DGCLAPJQ'],
            ['5CD53719TC', 'WRYDB0DGCLAGYZ'],
        ];

        $rows = [];

        foreach ($items as [$laptopSerial, $chargerSerial]) {
            $rows[] = [
                'category'              => 'LAPTOP',
                'item_name'             => 'HP Laptop 15-fc0566AU',
                'serial_number'         => $laptopSerial,
                'charger_serial_number' => $chargerSerial,
                'brand'                 => 'HP',
                'model'                 => '15-fc0566AU',
                'purchase_cost'         => 25000.00,
                'supplier'              => 'INFINITYTECH SOLUTION INC.',
                'purchase_order_no'     => null,
                'purchase_date'         => Carbon::create(2025, 12, 5),
                'warranty_expiration'   => null,
                'assigned_company'      => 'BTSMC',
                'assigned_employee'     => null,
                'location'              => 'Spark place cubao',
                'status'                => 'deployed',
                'condition'             => 'new',
                'remarks'               => null,
                'inclusions'            => json_encode(['bag', 'charger']),
                'created_at'            => now(),
                'updated_at'            => now(),
            ];
        }

        DB::table('it_leasings')->insert($rows);
    }
}
