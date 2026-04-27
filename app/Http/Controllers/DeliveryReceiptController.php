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
            ->select(['id', 'brand', 'model', 'serial_number','charger_serial_number', 'inclusions', 'assigned_employee', 'assigned_company'])
            ->get();

        $inclusionsMap = $items->mapWithKeys(function ($items) {
            return [$items->id => collect((array) ($items->inclusions ?? []))];
        });

            // \dd($items);
        abort_if($items->isEmpty(), 404, 'No items found.');

        $pdf = Pdf::loadView('pdf.delivery-receipt', compact('items', 'inclusionsMap'))
            ->setPaper('a4', 'portrait');

        $filename = 'DR-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->stream($filename);
    }
}
