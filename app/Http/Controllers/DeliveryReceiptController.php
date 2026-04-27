<?php

namespace App\Http\Controllers;

use App\Models\ItLeasing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DeliveryReceiptController extends Controller
{
    public function generate(Request $request)
    {
        $ids = array_filter(explode(',', $request->query('ids', '')));

        abort_if(empty($ids), 400, 'No items selected.');

        $items = ItLeasing::whereIn('id', $ids)
            ->select(['id', 'brand', 'model', 'serial_number', 'charger_serial_number', 'inclusions', 'assigned_employee', 'assigned_company'])
            ->get();

        abort_if($items->isEmpty(), 404, 'No items found.');

        // DR number: date-based muna
        $drNumber = 'DR' . now()->format('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        // Build inclusions map
        $inclusionsMap = $items->mapWithKeys(function ($item) {
            $inc = collect((array) ($item->inclusions ?? []));
            return [$item->id => [
                'has_charger' => $inc->contains('charger'),
                'has_bag'     => $inc->contains('bag'),
                'has_mouse'   => $inc->contains('mouse'),
            ]];
        });

        $pdf = Pdf::loadView('pdf.delivery-receipt', compact('items', 'inclusionsMap', 'drNumber'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('DR-' . $drNumber . '.pdf');
    }
}
