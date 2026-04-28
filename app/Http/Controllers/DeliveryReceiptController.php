<?php

namespace App\Http\Controllers;

use App\Models\DeliveryReceipt;
use App\Models\ItLeasing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // ✅ Generate DR number + save — both inside transaction
        $drNumber = DB::transaction(function () use ($ids, $items) {
            $lastDr = DeliveryReceipt::whereYear('created_at', now()->year)
                ->lockForUpdate()
                ->max('dr_number');

            $lastSeq = $lastDr ? (int) substr($lastDr, -4) : 0;
            $drNumber = 'DR' . now()->format('y') . str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);

            DeliveryReceipt::create([
                'dr_number'         => $drNumber,
                'it_leasing_ids'    => $ids,
                'assigned_company'  => $items->first()->assigned_company,
                'assigned_employee' => $items->first()->assigned_employee,
                'generated_by'      => auth()->user()->name ?? 'system',
            ]);

            return $drNumber;
        });

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
