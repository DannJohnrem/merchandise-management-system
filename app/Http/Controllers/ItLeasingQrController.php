<?php

namespace App\Http\Controllers;

use App\Models\ItLeasing;
use Illuminate\Http\Request;

class ItLeasingQrController extends Controller
{
    public function show(ItLeasing $item)
    {
        return view('it-leasing.qr-show', compact('item'));
    }
}
