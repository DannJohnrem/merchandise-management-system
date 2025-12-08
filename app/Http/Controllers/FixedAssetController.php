<?php

namespace App\Http\Controllers;

use App\Models\FixedAsset;
use Illuminate\Http\Request;

class FixedAssetController extends Controller
{
    public function show(FixedAsset $item)
    {
        // Example: load related models, if any
        // $item->load('assignedUser', 'supplier');

        return view('fixed-asset.show', compact('item'));
    }
}
