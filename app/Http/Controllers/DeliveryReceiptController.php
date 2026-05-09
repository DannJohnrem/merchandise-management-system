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
            ->select(['id', 'brand', 'model', 'serial_number', 'charger_serial_number', 'inclusions'])
            ->get();

        abort_if($items->isEmpty(), 404, 'No items found.');

        // Dynamic fields from modal form
        $shippedToCompany = $request->query('shipped_to_company', '');
        $shippedToAddress = $request->query('shipped_to_address', '');
        $billedToCompany  = $request->query('billed_to_company', $shippedToCompany);
        $billedToAddress  = $request->query('billed_to_address', $shippedToAddress);
        $releasedBy       = $request->query('released_by', 'ACJ SUMMIT VENTURES CORP');
        $receivedBy       = $request->query('received_by', '');

        $drNumber = DB::transaction(function () use ($ids, $items, $shippedToCompany) {
            $lastDr  = DeliveryReceipt::whereYear('created_at', now()->year)
                ->lockForUpdate()->max('dr_number');
            $lastSeq = $lastDr ? (int) substr($lastDr, -4) : 0;
            $drNumber = 'DR' . now()->format('y') . str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);

            DeliveryReceipt::create([
                'dr_number'         => $drNumber,
                'it_leasing_ids'    => $ids,
                'assigned_company'  => $shippedToCompany,
                'generated_by'      => auth()->user()->name ?? 'system',
            ]);

            return $drNumber;
        });

        $inclusionsMap = $items->mapWithKeys(function ($item) {
            $inc = collect((array) ($item->inclusions ?? []))
                ->map(fn($v) => strtolower(trim((string) $v)));

            return [$item->id => [
                'has_charger' => !empty($item->charger_serial_number),
                'has_bag'     => $inc->contains(fn($v) => str_contains($v, 'bag')),
                'has_mouse'   => $inc->contains(fn($v) => str_contains($v, 'mouse')),
            ]];
        });

        $pdf = Pdf::loadView('pdf.delivery-receipt', compact(
            'items',
            'inclusionsMap',
            'drNumber',
            'shippedToCompany',
            'shippedToAddress',
            'billedToCompany',
            'billedToAddress',
            'releasedBy',
            'receivedBy'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('DR-' . $drNumber . '.pdf');
    }
}
