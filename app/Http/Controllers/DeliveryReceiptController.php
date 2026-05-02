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
            $raw = $item->inclusions;

            // Safe decode kung hindi pa array
            if (is_string($raw)) {
                $raw = json_decode($raw, true) ?? [];
            }

            // Lowercase lahat para sa flexible matching
            $inc = collect((array) $raw)
                ->map(fn($v) => strtolower(trim((string) $v)));

            return [$item->id => [
                'has_charger' => $inc->contains(fn($v) => str_contains($v, 'charger')),
                'has_bag'     => $inc->contains(fn($v) => str_contains($v, 'bag')),
                'has_mouse'   => $inc->contains(fn($v) => str_contains($v, 'mouse')),
            ]];
        });

        $pdf = Pdf::loadView('pdf.delivery-receipt', compact('items', 'inclusionsMap', 'drNumber'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('DR-' . $drNumber . '.pdf');
    }
}
