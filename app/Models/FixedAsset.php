<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_tag',            // Unique tag ng asset (FA-0001, LAP-023, PRN-004)
        'category',             // Laptop, Printer, Office Equipment, Furniture, Supplies, etc.
        'asset_name',           // Pangalan ng item (ex: Dell Latitude 3420 Laptop)
        'serial_number',        // Serial number ng device; supplies = null
        'brand',                // Dell, HP, Epson, Acer, Brother, etc.
        'model',                // Latitude 3420, Thinkpad L13, Epson L3110, etc.
        'cost',                 // Purchase cost
        'supplier',             // PCExpress, etc.
        'assigned_to',          // Employee or Department
        'class',                // IT Assets, Office Supplies
        'location',             // Office location
        'status',               // active, damaged, for-repair
        'condition',            // good, very good, poor
        'warranty_expiration',  // Warranty end date
        'purchase_date',        // Purchase date
        'purchase_order_no',    // PO number
        'remarks',              // Notes
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'purchase_date' => 'date',
        'warranty_expiration' => 'date',
    ];
}
